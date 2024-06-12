<?php
namespace App\Http\Controllers;
use Auth;
use Session;
use App\Models\Deposit;
use App\Models\DepositHistory;
use Mail;
use App\Mail\Deposit\depositRequestUserEmail;
use App\Mail\Depoist\depositRequestAdminNotificationEmail;
use App\Models\Wallet;
use App\Models\Currency;
use App\Models\DepositMethod;
use App\Models\TransferMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Money;
class AddCreditController extends Controller
{
    public function __construct()
    {
        $this->deposit_fixed_fee = 1; 
        $this->deposit_percentage_fee = 0; 
    }
    public function AddCreditForm( $lang, $method_id = false )
    {
        if(Auth::user()->currentWallet() == null){
            return redirect(route('show.currencies', app()->getLocale()));
        }
        $methods = Auth::user()->currentCurrency()->DepositMethods()->get();
    	if ($method_id) {
    		$current_method = DepositMethod::where('id', $method_id)->with('currencies')->first();
    		if ($current_method == null) {
    			dd('please contact admin to link a deposit method to '.Auth::user()->currentCurrency()->name.' currency');
    		}
    	}else{
            if (isset($methods[0]) ) {
               $current_method = $methods[0];
            } else{
                dd('please contact admin to link a deposit method to '.Auth::user()->currentCurrency()->name.' currency');
            }
    	}
    	
        $currencies = Currency::where('id' , '!=', Auth::user()->currentCurrency()->id)->get();
    	return view('deposits.addCreditForm')
    	->with('current_method', $current_method)
        ->with('currencies', $currencies)
    	->with('methods', $methods);
    }
    public function depositMethods( )
    {
        $methods = DepositMethod::all();
        return view('deposits.methods')->with('methods', $methods);
    }
    public function depositTransferMethods(Request $request, $lang, $id)
    {
        $transferMethods = TransferMethod::where('currency_id',Auth::user()->currentWallet()->currency_id )->where('is_hidden', 0)->where('is_system', 0)->paginate(10);
        // dd($transferMethods);
        return view('deposits.methods')->with('transferMethods',  $transferMethods)->with('wid', $id);
    }
    //DEPRECATED
    public function depositByWallet(Request $request, $lang, $id)
    {
        return view('deposits.transfer')->with('transferMethod', Auth::user()->currentWallet()->transferMethod)->with('wid', $id);
    }
    public function depositByTransferMethod(Request $request, $lang, $id)
    {
        $transferMethod = TransferMethod::where('id', $id)->where('is_hidden', 0)->where('is_system', 0)->first();
        if( $transferMethod == null )
        {
            dd('undefined transfer method');
        }
        $wallet_id = Auth::user()->currentWallet()->id ;
        $payment_name = isset($transferMethod->name) ? $transferMethod->name:'';
        if($payment_name == 'flutterwave')
        {
            return view('deposits.flutter_deposit_form')->with('transferMethod',$transferMethod)->with('wid',   $wallet_id);
        }
        else
        {
            return view('deposits.transfer')->with('transferMethod',$transferMethod)->with('wid',   $wallet_id);
        }
    }
    public function depositRequest( Request $request, $laang){
    	$this->validate($request, [
    		'deposit_screenshot'	=> 'required|mimes:jpg,png,jpeg',
            'unique_transaction_id'  =>  'required',
            'tmid'  =>  'required|exists:transfer_methods,id',
            'wid' =>  'required|exists:wallets,id',
    	]);
           
        $transferMethod = TransferMethod::findOrFail($request->tmid);
        $wallet = Wallet::findOrFail($request->wid);
        if($wallet->user_id != Auth::user()->id){
            abort(404);
        }
    	if ( $request->hasFile('deposit_screenshot') ) {
    		$file = $request->file('deposit_screenshot');
    		$path = 'users/'.Auth::user()->name.'/deposits/'.preg_replace('/\s/', '', $file->getClientOriginalName());
    		Storage::put($path, $file);
    		$local_path = Storage::put($path, $file);
    		$link = Storage::url($local_path);
    	}
    	$depositRequest = Deposit::create([
    		'user_id'	=>	Auth::user()->id,
            'wallet_id' =>  $wallet->id,
            'currency_id'   =>  $transferMethod->currency_id, 
            'currency_symbol'   =>  $transferMethod->currency->symbol,
    		'transaction_state_id'	=>	3,
    		'deposit_method_id'	=>	1,
    		'gross'	=>	0,
    		'fee'	=>	0,
    		'net'	=>	0,
            'message'   =>  $request->message ? $request->message: 'No Message',
    		'transaction_receipt'	=>	$link,
    		'json_data'	=>	'{"deposit_screenshot":"'.$path.'"}',
            'transfer_method_id' => $transferMethod->id,
            'unique_transaction_id' => $request->unique_transaction_id,
    	]);
        //send notification to admin
        
        //Mail::send(new depositRequestAdminNotificationEmail( $depositRequest, Auth::user()));
        //Send new deposit request notification Mail to user
        Mail::send(new depositRequestUserEmail( $depositRequest, Auth::user()));
    	flash('Your Deposit is Waiting for a review', 'info');
    	return  redirect(route('mydeposits', app()->getLocale()));
    }
    public function flutteraddcredit( Request $request, $laang)
    {
        $this->validate($request, [
            'amount'  =>  'required',
            'tmid'  =>  'required|exists:transfer_methods,id',
            'wid' =>  'required|exists:wallets,id',
        ]);
        $transferMethod = TransferMethod::findOrFail($request->tmid);
        $wallet = Wallet::findOrFail($request->wid);
        if($wallet->user_id != Auth::user()->id)
        {
            abort(404);
        }
        $wallet_id = Auth::user()->currentWallet()->id;
        $userDetails = Auth::user();
        $amount = isset($request->amount) ? $request->amount:0;
        $total_fee = 0;
        $fixed_fee = $this->deposit_fixed_fee;
        $percentage_fee = ($amount/100) * $this->deposit_percentage_fee;
        $total_fee = ($fixed_fee + $percentage_fee);
        $total_amount = $amount + $total_fee;
        Session::put('amount', $amount);
        Session::put('tmid', $request->tmid);
        Session::put('wid', $request->wid);
        $trx = Money::getTrx();
        $tx_ref = Money::getTrx();
        return view('deposits.flutter_pay')->with('transferMethod',$transferMethod)->with('wid',$wallet_id)->with('amount',$total_amount)->with('userDetails',$userDetails)->with('tx_ref',$tx_ref);
    }
    public function handleFlutterWavePayment()
    {
        $data = $_REQUEST;
        $status = $data['status'];
        $tx_ref = $data['tx_ref'];
        $transaction_id = $data['transaction_id'];
        $amount = Session::get('amount');
        $tmid = Session::get('tmid');
        $wid = Session::get('wid');
        $wallet = Wallet::findOrFail($wid);
        $transferMethod = TransferMethod::findOrFail($tmid);
        if($status == 'successful' || $status == 'completed' || $status == 1 || $status == true)
        {
            $total_fee = 0;
            $fixed_fee = $this->deposit_fixed_fee;
            $percentage_fee = ($amount/100) * $this->deposit_percentage_fee;
            $total_fee = ($fixed_fee + $percentage_fee);

            $model = new DepositHistory();
            $model->user_id = Auth::user()->id;
            $model->transaction_id = $transaction_id;
            $model->tx_ref = $tx_ref;
            $model->status = $status;
            $model->fee = $total_fee;
            $model->amount = $amount;
            $model->save();
            
            $deposit = new Deposit();
            $deposit->user_id = Auth::user()->id;
            $deposit->wallet_id = $wallet->id;
            $deposit->currency_id = $transferMethod->currency_id;
            $deposit->currency_symbol = $transferMethod->currency->symbol;
            $deposit->transaction_state_id = 1;
            $deposit->deposit_method_id = 1;
            $deposit->gross = $amount + $total_fee;
            $deposit->fee = $total_fee;
            $deposit->net = $amount;
            $deposit->message = $status;
            $deposit->json_data = '{"status":"'.$status.'"}';
            $deposit->transfer_method_id = $transferMethod->id;
            $deposit->unique_transaction_id = $transaction_id;
            $deposit->save();

            $wallet->amount+=$amount;
            $wallet->save();
            if(session()->has('amount'))
            {
                session()->forget('amount');
            }
            if(session()->has('tmid'))
            {
                session()->forget('tmid');
            }
            if(session()->has('wid'))
            {
                session()->forget('wid');
            }
            flash('Your Deposit is done successfully', 'info');
            return redirect(url('/').'/'.app()->getLocale().'/deposit'.'/'.Auth::user()->currentWallet()->id);
        }
        
    }
}
