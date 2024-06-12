<?php
namespace App\Http\Controllers;
use Auth;
use Mail;
use App\User;
use App\Mail\Withdrawal\withdrawalRequestUserEmail;
use App\Mail\Withdrawal\withdrawalRequestAdminNotificationEmail;
use App\Mail\Withdrawal\withdrawalCompletedUserNotificationEmail;
use App\Models\TransactionState;
use App\Models\TransferMethod;
use App\Models\Transaction;
use App\Models\Currency;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Models\WithdrawalMethod;
use App\Helpers\Money;
use App\Models\Merchant;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use App\Models\WhiteListAccount;

class WithdrawalController extends Controller
{
    public function index(Request $request, $lang){
        if(Auth::user()->currentWallet() == null){
            return redirect(route('show.currencies', app()->getLocale()));
        }
        $withdrawals = Withdrawal::with(['transferMethod','Status', 'Method'])->where('user_id', Auth::user()->id)->orderby('id', 'desc')->paginate(10);

        return view('withdrawals.index')->with('withdrawals', $withdrawals);
    }
    public function payoutMethod(Request $request, $lang, $wallet_id){
        if(Auth::user()->currentWallet() == null){
            return redirect(route('show.currencies', app()->getLocale()));
        }
        $methods = Auth::user()->currentWallet()->TransferMethods()->where('is_system', 0)->where('is_hidden', 0)->get();
        $wallet = Wallet::findOrFail($wallet_id);
        if($wallet_id != Auth::user()->currentWallet()->id){
            abort(404);
        } 
        $transferMethod = TransferMethod::findOrFail($wallet->transfer_method_id);
        return view('withdrawals.methods')
        ->with('methods',  $methods)
        ->with('wallet', $wallet)
        ->with('transferMethod', $transferMethod)
        ->with('wid', $wallet->id);
    }
    public function payoutForm(Request $request, $lang, $pivot_id){
        if(Auth::user()->currentWallet() == null){
            return redirect(route('show.currencies', app()->getLocale()));
        }
        $transferMethod = Auth::user()->currentWallet()->TransferMethods()->where('is_system', 0)->where('is_hidden', 0)->having('pivot_id', $pivot_id)->first();
      
        $wallet = Auth::user()->currentWallet();
        // validar a carteira do usuario.
        if($transferMethod->pivot->wallet_id != Auth::user()->currentWallet()->id){
            dd('not your wallet');
        } 
      
        return view('withdrawals.transfer')
        ->with('transferMethod', $transferMethod)
        ->with('wid', $wallet->id)
        ->with('pivx', $pivot_id);
    }
    public function makeRequest(Request $request, $lang){
        //HERE
        
        $wallet = Wallet::with('currency')->findOrFail($request->wid);
        if($wallet->id != Auth::user()->currentWallet()->id){
            abort(404);
        }
        $transferMethod = Auth::user()->currentWallet()->TransferMethods()->where('is_system', 0)->where('is_hidden', 0)->having('pivot_id', $request->pivx)->first();
        if ($transferMethod == null) {
            abort(404);
        }
        $this->validate($request, [
            'amount'   =>  'required|numeric',
        ]);
        if($wallet->amount < $request->amount){
            flash(__('your balance is not enouth to withdrawal '. $request->amount) , 'danger');
            return  back();
        }
        if ( $wallet->is_crypto == 1 ){
            $precision = 8 ;
        } else {
            $precision = 2;
        }
        if ( Auth::user()->account_status == 0 ) {
            flash(__('Your account is under a withdrawal request review proccess. Please wait until your request is complete in a few minutes to continue with your activities.') , 'danger');
             return  back();
        }
        $withdraw_fee = bcadd( bcmul ( ( $transferMethod->withdraw_percentage_fee / 100 ), $request->amount, $precision) , $transferMethod->withdraw_fixed_fee, $precision ) ;
    
        $withdraw_net = bcsub($request->amount, $withdraw_fee, $precision );
        if( $withdraw_net <= 0 ){
            flash(__('Invalid Amount ('. $withdraw_net . ' '.  $wallet->currency->symbol . ') ' ) , 'danger');
             return  back();
        }
    	
        $withdrawal = Withdrawal::create([
            'user_id'   =>  Auth::user()->id,
            'transaction_state_id'  =>  3,
            'transfer_method_id'    =>  $transferMethod->id,
            'withdrawal_method_id'  => 1,
            'platform_id'  =>  $transferMethod->pivot->adress,
            'send_to_platform_name' =>  $transferMethod->name,
            'gross' =>  $request->amount,
            'fee'   =>  $withdraw_fee,
            'currency_id'   =>  $wallet->currency_id,
            'currency_symbol'   =>  $wallet->currency->symbol,
            'wallet_id' => $wallet->id,
            'net'   =>   $withdraw_net,
        ]);
        // Send Alert to Admin 
        Mail::send(new withdrawalRequestAdminNotificationEmail($withdrawal, Auth::user()));
        //Send new withdraw request notification Mail to user
        Mail::send(new withdrawalRequestUserEmail( $withdrawal, Auth::user()));
        return redirect(route('withdrawal.index', app()->getLocale()));
    }
    public function confirmWithdrawal(Request $request, $lang){
        
        if (!Auth::user()->isAdministrator()) {
            abort (404);
        }
        $withdrawal = Withdrawal::with('transferMethod')->findOrFail($request->id);
        $transferMethod = TransferMethod::findOrFail($withdrawal->transfer_method_id);
        if($withdrawal->transaction_state_id != 3 and $withdrawal->transaction_state_id != 2 ) 
        {
            flash(__('Transaction Already completed !'), 'info' );
            //return redirect(url('/').'/admin/withdrawals/'.$withdrawal->id);
            return back();
        }
        $user = User::findOrFail($request->user_id);
        $wallet = Wallet::findOrFail($withdrawal->wallet_id);
        if($wallet->is_crypto == 1 )
        {
            $precision = 8 ;
        } 
        else 
        {
            $precision = 2;
        }
        if($wallet->amount < $withdrawal->gross) 
        {
            flash('User doesen\'t have enought funds to withdraw '.$withdrawal->gross.' $', 'danger' );
            return back();
        }
        if($request->transaction_state_id == 1 )
        {
            
            $wallet->amount = bcsub($wallet->amount ,$withdrawal->gross, $precision);
        } 
        else 
        {
            $state = TransactionState::findOrFail($request->transaction_state_id);
            dd( 'Withdrawal stil  ' . $state->name);
        }
        $user->RecentActivity()->save($withdrawal->Transactions()->create([
            'user_id' => $user->id,
            'entity_id'   =>  $user->id,
            'entity_name' =>  $transferMethod->name,
            'transaction_state_id'  =>  $request->transaction_state_id, // waiting confirmation
            'money_flow'    => '-',
            'activity_title'    =>  'Withdrawal',
            'balance'   =>   $wallet->amount,
            'thumb' =>  $transferMethod->thumbnail,
            'gross' =>  $withdrawal->gross,
            'fee'   =>  $withdrawal->fee,
            'net'   =>  $withdrawal->net,
            'currency_id'   =>  $withdrawal->currency_id,
            'currency_symbol'   =>  $withdrawal->currency_symbol,
        ]));
        
        $withdrawal->transaction_state_id = $request->transaction_state_id;
        $withdrawal->save();
        $user->account_status = 1;
        $wallet->save();
        $user->save();
        //Send Notification to User
        Mail::send(new withdrawalCompletedUserNotificationEmail($withdrawal, $user));
        
        return redirect(url('/').'/admin/dashboard/withdrawals/'.$withdrawal->id);
        
    }
    // DEPRECATED
    public function getWithdrawalRequestForm(Request $request, $method_id = false){
        $methods = Auth::user()->currentCurrency()->WithdrawalMethods()->get();
        if ($method_id) {
            $current_method = WithdrawalMethod::where('id', $method_id)->first();
            if ($current_method == null) {
                dd('please contact admin to link a withdrawal method to '.Auth::user()->currentCurrency()->name.' currency');
            }
        }else{
            if (isset($methods[0]) ) {
               $current_method = $methods[0];
            } else{
                dd('please contact admin to link a withdraw method to '.Auth::user()->currentCurrency()->name.' currency');
            }
        }
        
        $currencies = Currency::where('id' , '!=', Auth::user()->currentCurrency()->id)->get();
        return view('withdrawals.withdrawalRequestForm')
        ->with('current_method', $current_method)
        ->with('currencies', $currencies)
        ->with('methods', $methods);
    }
    public function add()
    {
        $data['withdrawalMethod'] = WithdrawalMethod::with('currencies')->whereStatus(1)->orderBy('sequence_no', 'asc')->get();
        $data['whitelistacount'] = WhiteListAccount::where('user_id',Auth::user()->id)->where('status','approved')->get();

        $wallet = Auth::user()->currentWallet()->exchangeRates;
        // $currencies = Currency::find
        $exchanges = $wallet->mapWithKeys(function ($item, int $key) {
            return [$item['second_currency_id'] => [
                'first_currecny_name' =>  Currency::find($item['first_currency_id'])->name,
                'second_currency_name' =>  Currency::find($item['second_currency_id'])->name,
                'rate' => $item['exchanges_to_second_currency_value']
            ]];
        });

        $data['exchanges'] = $exchanges->all();
        
        return view('withdrawals.add_method',$data);
    }
    public function create(Request $request)
    {
        $withdrawal_methods = WithdrawalMethod::findOrFail($request->withdrawal_method_id);
        $currency_id = isset($withdrawal_methods->currency_id) ? $withdrawal_methods->currency_id:'';
        $method_name = isset($withdrawal_methods->name) ? $withdrawal_methods->name:'';
        $currency = Currency::findOrFail($currency_id);
        $currency_symbol = isset($currency->symbol) ? $currency->symbol:'';
        $wallet = Wallet::where(['user_id'=>auth()->user()->id])->where(['currency_id'=>$currency_id])->first();
        $merchant_status = Merchant::where(['user_id'=>auth()->user()->id])->pluck('time_status')->first();

        if(!$wallet)
        {
            flash(__('Wallet not found!'), 'danger');
            return back();
        }
        $wallet_id = isset($wallet->id) ? $wallet->id:'';
        $user = User::find(auth()->user()->id);
        $trx = Money::getTrx();
        $balance = $wallet->fiat;
        $user_id = auth()->user()->id;
        $amount = $request->amount;
        
        if($amount > $wallet->fiat)
        {
            flash(__('Low balance!'), 'danger');
            return back();
        }

        $setting = Setting::where('key','time_status')->first();
        if($setting)
        {
           $value = $setting->value;
         
            if ($value == '24') {
               $merchant_status = '24';
           }elseif ($value == '48') {
               $merchant_status = '48';
           }elseif ($value == '72') {
               $merchant_status = '72';
           }else{
                $merchant_status = '0';
           }
        }
 

        if ($merchant_status == '24') {
            
            $get_todays_balance = Transaction::where('user_id',$user_id)->whereIn('activity_title',['Sale','Manual Deposit From Dashboard'])->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString())->get();

            $netBalance = 0;
            foreach($get_todays_balance as $key => $value){
                $netBalance += $value['net'];
            }

            $can_payout = $balance - $netBalance;

            if ($amount > $can_payout) {
                flash(__('You can only withdrawal upto '. $can_payout), 'danger');
                return back();
              
            }

        }

        if ($merchant_status == '48') {

            
            $get_todays_balance = Transaction::where('user_id',$user_id)->whereIn('activity_title',['Sale','Manual Deposit From Dashboard'])->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString(2))->get();


            $netBalance = 0;
            foreach($get_todays_balance as $key => $value){
                $netBalance += $value['net'];
            }
           
            $can_payout = $balance - $netBalance;
            
            if ($amount > $can_payout) {
                flash(__('You can only withdrawal upto '. $can_payout), 'danger');
                return back();
            }

        }

         if ($merchant_status == '72') {
            
            $get_todays_balance = Transaction::where('user_id',$user_id)->whereIn('activity_title',['Sale','Manual Deposit From Dashboard'])->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString(3))->get();

            $netBalance = 0;
            foreach($get_todays_balance as $key => $value){
                $netBalance += $value['net'];
            }

            $can_payout = $balance - $netBalance;

            if ($amount > $can_payout) {
                flash(__('You can only withdrawal upto '. $can_payout), 'danger');
                return back();
            }

        }

        $total_fee = 0;
        $fee_in_amount = Money::getSetting('withdraw_fixed_fee');
        $fee_in_percentage = Money::getSetting('withdraw_percentage_fee');
        $total_fee_percentage = ($amount/100) * $fee_in_percentage;
        $total_fee = $total_fee_percentage + $fee_in_amount;

        $bank_account = $request->withdrawal_bank;
        if ($bank_account) {
            $explode = explode('-',$bank_account);
            $bank_details = 'Name:-'.$explode[0].' -  Acc No: ' .$explode[1].'- IFSC: ' .$explode[2];
        }

        $withdrawal = new Withdrawal;
        $withdrawal->user_id = $user->id;
        $withdrawal->withdrawal_method_id = $request->withdrawal_method_id;
        $withdrawal->gross = $amount;
        $withdrawal->fee = $total_fee;
        $withdrawal->net = $amount;
        $withdrawal->unique_transaction_id = $trx;
        $withdrawal->transaction_state_id = 3;
        $withdrawal->currency_symbol = $currency_symbol;
        $withdrawal->wallet_id = $wallet_id;
        $withdrawal->send_to_platform_name = $method_name;
        $withdrawal->currency_id = $currency_id;
        $withdrawal->transfer_method_id = $request->withdrawal_method_id;
        $withdrawal->detail = isset($request->detail) ? $request->detail : $bank_details;
        Transaction::create([
            'user_id' =>  $user->id,
            'entity_id'   =>  $user->id,
            'request_id'=>isset($trx) ? $trx:'',
            'transactionable_id'=>isset($trx) ? $trx:'',
            'entity_name' =>  $wallet->currency->name,
            'transaction_state_id'  =>  3, // waiting confirmation
            'money_flow'    => '-',
            'activity_title'    =>  'Manual Withdraw',
            'currency_id' =>  $wallet->currency->id,
            'currency_symbol' =>  $wallet->currency->symbol,
            'thumb' =>  $wallet->currency->thumb,
            'gross' =>  $amount,
            'fee'   =>  $total_fee,
            'net'   =>  $amount,
            'balance'   =>  $wallet->fiat - $amount,
        ]);
        $wallet->fiat-=$amount;
        $wallet->save();
        $withdrawal->save();
        flash(__('Withdraw Method added Successfully'), 'success');
        return redirect(route('withdrawal.index', app()->getLocale()));
    }
}
