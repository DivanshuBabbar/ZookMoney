<?php

namespace App\Http\Controllers;

use App\Helpers\Money;
use App\Mail\ExpressCheckoutOtpMail;
use App\Mail\ExpressCheckoutRegisterMail;
use Illuminate\Support\Facades\Auth;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\DepositMethod;
use App\Models\ExpressCheckoutOtp;
use App\User;
use App\Models\Purchase;
use App\Models\PurchaseRequest;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\Merchant;
use App\Models\TransferMethod;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\TransactionLogs;

class IPNController extends Controller
{
    public function pay(Request $request, $lang)
    {
        return redirect(route('logandpay', app()->getLocale()));
    }

    // The former name of oldLogAndPayWithoutAutoConfirmation() method was logandpay() 
    // An extra layer of confirmation of paymnet from user panel (which is deprecated) needed if we use this method to pay.
    // purchaseConfirmation() was used to confirm purchase which is also deprecated.

    // deprecated
    public function oldLogAndPayWithoutAutoConfirmation(Request $request, $lang)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255|exists:users,email',
            'password' => 'required|string|min:5',
            'ref' => 'required|numeric',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            flash(__('Your email and password do not match our records.'), 'danger');
            return back();
        }

        $merchant = Merchant::with('User')
            ->where('merchant_key', session()->get('PurchaseRequest')->merchant_key)
            ->first();

        if ($merchant == null) {
            flash(__('Merchant Not Found.'), 'danger');
            return back();
        }

        $PurchaseRequest = PurchaseRequest::where('ref', $request->ref)->first();

        $currency = Currency::where('code', $PurchaseRequest->currency_code)->first();

        if ($currency->is_crypto == 1) {
            $precision = 8;
        } else {
            $precision = 2;
        }

        $auth_wallet = Auth::user()->walletByCurrencyId($currency->id);

        if (is_null($auth_wallet)) {
            //return redirect(app()->getLocale().'/transfer/methods');
            return redirect(route('show.currencies', app()->getLocale()));
        }

        if ((bool) $currency == false) {
            flash(__('Wops, something went wrong... looks like we do not support this currency. please contact support if this error persists !'), 'danger');
            return back();
        }

        if ($PurchaseRequest->Transaction != null) {
            flash(__('This purchase request link is already been used. please go back to') . ' <a class="btn btn-xs btn-danger" href="' . $merchant->site_url . '">' . $merchant->name . '</a>' . __('and try to purchase again !'), 'danger');
            return back();
        }

        if ($PurchaseRequest == null) {
            flash(__('Woops... Something went wrong !'), 'danger');
            return back();
        }

        if (Auth::user()->account_status == 0) {
            flash(__('Your account is under a withdrawal request review proccess. please wait for a few minutes and try again'), 'info');
            return back();
        }

        if (Auth::user()->id == $merchant->User->id) {
            Auth::logout();

            flash(__('You are logging into the account of the seller for this purchase. Please change your login information and try again.'), 'danger');
            return back();
        }

        if ($auth_wallet->amount < (float) session()->get('PurchaseRequestTotal')) {
            Auth::logout();

            flash(__('You have insufficient funds in your ') . $currency->name . __(' wallet to proceed with this purchase.'), 'danger');
            return back();
        }

        $purchase_fee = 0; //free buy with your site credit

        $sale_fee = bcadd(bcmul('' . $merchant->merchant_percentage_fee / 100, '' . session()->get('PurchaseRequestTotal'), $precision), $merchant->merchant_fixed_fee, $precision);

        if ($currency->is_crypto == 1) {
            $sale_fee = bcmul('' . $merchant->merchant_percentage_fee / 100, '' . session()->get('PurchaseRequestTotal'), $precision);
        }

        $minimum = (float) session()->get('PurchaseRequestTotal') + (float) $sale_fee;

        if ((float) session()->get('PurchaseRequestTotal') - $sale_fee <= 0) {
            flash(__('We only support invoices with a total greater than ') . $minimum . $currency->symbol, 'danger');
            return back();
        }

        DB::beginTransaction();

        try {
            $sale = Sale::create([
                'user_id' => $merchant->User->id,
                'merchant_id' => $merchant->id,
                //'purchase_id' =>  0,
                'transaction_state_id' => 1,
                'gross' => (float) session()->get('PurchaseRequestTotal'),
                'fee' => $sale_fee,
                'net' => bcsub('' . session()->get('PurchaseRequestTotal'), $sale_fee, $precision),
                'currency_id' => $currency->id,
                'currency_symbol' => $currency->symbol,
                'json_data' => json_encode($PurchaseRequest->data),
            ]);
    
            $purchase = Purchase::create([
                'user_id' => Auth::user()->id,
                'merchant_id' => $merchant->id,
                'sale_id' => $sale->id,
                'transaction_state_id' => 1,
                'currency_id' => $currency->id,
                'currency_symbol' => $currency->symbol,
                'gross' => (float) session()->get('PurchaseRequestTotal'),
                'fee' => $purchase_fee,
                'net' => bcsub('' . session()->get('PurchaseRequestTotal'), $purchase_fee, $precision),
                'json_data' => json_encode($PurchaseRequest->data),
            ]);
    
            $merchant->User->RecentActivity()->save(
                $sale->Transactions()->create([
                    'user_id' => $sale->user_id,
                    'entity_id' => $merchant->id,
                    'entity_name' => $merchant->name,
                    'transaction_state_id' => 3, // waiting confirmation
                    'money_flow' => '+',
                    'currency_id' => $currency->id,
                    'currency_symbol' => $currency->symbol,
                    'thumb' => $purchase->User->avatar(),
                    'activity_title' => 'Sale',
                    'gross' => $sale->gross,
                    'fee' => $sale->fee,
                    'net' => $sale->net,
                    'request_id' => $PurchaseRequest->id,
                    'json_data' => json_encode($PurchaseRequest->data),
                ])
            );
    
            Auth::user()
                ->RecentActivity()
                ->save(
                    $purchase->Transactions()->create([
                        'user_id' => Auth::user()->id,
                        'entity_id' => $merchant->id,
                        'entity_name' => $merchant->name,
                        'transaction_state_id' => 3, // waiting confirmation
                        'money_flow' => '-',
                        'activity_title' => 'Purchase',
                        'currency_id' => $currency->id,
                        'thumb' => $merchant->logo,
                        'currency_symbol' => $currency->symbol,
                        'gross' => $purchase->gross,
                        'fee' => $purchase->fee,
                        'net' => $purchase->net,
                        'request_id' => $PurchaseRequest->id,
                        'json_data' => json_encode($PurchaseRequest->data),
                    ])
                );

        } catch (\Exception $e) {
            DB::rollBack();

            flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');
            return back();
        }

        DB::commit();
        return redirect(route('home', app()->getLocale()));

    }
    // deprecated
    public function purchaseConfirmation(Request $request, $lang)
    {
        $this->validate($request, [
            'tid' => 'required|numeric',
        ]);

        $transaction = Transaction::findOrFail($request->tid);

        $currency = Currency::find($transaction->currency_id);

        if ($currency->is_crypto == 1) {
            $precision = 8;
        } else {
            $precision = 2;
        }

        $auth_wallet = Auth::user()->walletByCurrencyId($currency->id);

        if ((bool) $currency == false) {
            flash(__('Wops, something went wrong... looks like we do not support this currency. please contact support if this error persists !'), 'danger');
            return back();
        }

        if (Auth::user()->currentCurrency()->id != $currency->id) {
            $CurrentUser = Auth::user();
            $CurrentUser->currency_id = $currency->id;
            $CurrentUser->save();

            // flash(__('Wops, something went wrong... please change your wallet to ').$currency->name. __(' to proceed with this transaction, contact support if this error persists !'), 'danger');

            flash(__('Something Went Wrong. Please Try to confirm this payment one more time'));

            return back();
        }

        $purchaseRequest = PurchaseRequest::findOrFail($transaction->request_id);

        if (
            $transaction->created_at <
            Carbon::now()
                ->subMinutes(5)
                ->toDateTimeString()
        ) {
            flash(__('The purchase transaction you are trying to confirm was created ') . $transaction->created_at->diffForHumans() . __(' and at this time the item may not exist in the stock.<br> Please, delete this transaction, go back to ') . $transaction->entity_name . '\'s' . __(' site and try again'), 'warning');
            $purchaseRequest->is_expired = true;
            $purchaseRequest->save();
            return back();
        }

        if ((bool) $transaction == false) {
            flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');
            return back();
        }

        if (Auth::user()->account_status == 0) {
            flash(__('Your account is under a withdrawal request review proccess. please wait for a few minutes and try again'), 'info');
            return back();
        }

        if (Auth::user()->id != $transaction->user_id) {
            flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');
            return back();
        }

        $purchase = Purchase::find($transaction->transactionable_id);

        if ((bool) $purchase == false) {
            flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');
            return back();
        }

        if (Auth::user()->id != $purchase->user_id) {
            flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');
            return back();
        }

        $sale = Sale::find($purchase->sale_id);

        if ((bool) $sale == false) {
            flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');
            return back();
        }

        $user = User::find($sale->user_id);

        $user_wallet = $user->walletByCurrencyId($currency->id);

        if ((bool) $user == false) {
            flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');
            return back();
        }

        $sale_transaction = transaction::where('transactionable_type', 'App\Models\Sale')
            ->where('user_id', $user->id)
            ->where('transaction_state_id', 3)
            ->where('money_flow', '+')
            ->where('transactionable_id', $sale->id)
            ->first();

        if ((bool) $sale_transaction == false) {
            flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');
            return back();
        }

        if ((float) $auth_wallet->amount < (float) $transaction->net) {
            flash(__('You have insufficient funds in you ') . $currency->name . __(' wallet to proceed with this purchase.'), 'danger');
            return back();
        }

        $sale->purchase_id = $purchase->id;
        $sale->transaction_state_id = 1;
        $sale->save();

        $purchase->transaction_state_id = 1;
        $purchase->save();

        $transaction->transaction_state_id = 1;
        $transaction->balance = bcsub($auth_wallet->amount, $transaction->net, $precision);
        $transaction->save();

        $sale_transaction->transaction_state_id = 1;
        $sale_transaction->balance = bcadd($user_wallet->amount, $sale_transaction->net, $precision);
        $sale_transaction->save();

        $auth_wallet->amount = bcsub($auth_wallet->amount, $transaction->net, $precision);
        $auth_wallet->save();

        $user_wallet->amount = bcadd($user_wallet->amount, $sale_transaction->net, $precision);
        $user_wallet->save();

        $this->sendIpnNotification($transaction);

        //flash('Transaction Complete !', 'success');

        Auth::logout();

        //return redirect($purchaseRequest->data->return_url.'token='.$purchaseRequest->ref);
        return redirect(http_build_url($purchaseRequest->data->return_url, ['query' => "token=$purchaseRequest->ref"], HTTP_URL_JOIN_QUERY));

        //return  back();
    }
    public function purchaseCancelation(Request $request, $lang)
    {
        $this->validate($request, [
            'tid' => 'required|numeric',
        ]);

        $transaction = Transaction::findOrFail($request->tid);

        $purchase = Purchase::findOrFail($transaction->transactionable_id);

        $sale = Sale::findOrFail($purchase->sale_id);

        $sale->delete();
        $purchase->delete();
        $transaction->delete();

        return back();
    }

    public function loginForPayment(Request $request, $lang) 
    {
        $ref = $request->ref;
        $amount = $request->amount ?? '0.00';
        
        if (empty($ref)) {
            return response()->json(['message' => 'No reference provided'], 422);
        }

        $this->validate($request, [
            'email' => 'required|string|email|max:255|exists:users,email',
            'password' => 'required|string|min:5',
            'ref' => 'required|numeric',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Your email and password do not match our records.'], 422);
        }
         
        $availableBalance = $this->getAvailableBalance($ref);
        
        $logs = new TransactionLogs();
        $logs->user_id = Auth::user()->id;
        $logs->ref = $ref;
        $logs->ip = \Request::ip();
        $logs->user_agent = \Request::server('HTTP_USER_AGENT');
        $logs->referrer = \Request::header('referer') ?? '';
        $logs->page_link = \Request::url();
        $logs->amount = $amount;
        $logs->action =  'Login Clicked';
        $logs->message = 'Redirected To Pay With Wallet Page';
        $logs->save();

        return response()->json([
            'html' =>  view('merchant.partials.balance', compact('availableBalance', 'ref','amount'))->render()
        ]);

    }

    function showLogin(Request $request, $lang,$ref)
    {
        
        $request = PurchaseRequest::where('ref',$ref)->first();
        $amount = $request['data']->total ?? 0;

        $merchant = Merchant::where('merchant_key',$request->merchant_key)->first();
        $availableBalance = '';
        if (Auth::user()) {
            $availableBalance = $this->getAvailableBalance($ref);            
        }
        $depositMethod = DepositMethod::get();
        
        return view('merchant.storefront',compact('ref','merchant','availableBalance','depositMethod','amount'));
        return response()->json([
            'html' =>  view('merchant.partials.login')->render()
        ]);
    }

    function showExpressLogin(Request $request, $lang,$ref)
    {  
       
        return view('merchant.partials.express-login' ,compact('ref'));

        return response()->json([
            'html' =>  view('merchant.partials.express-login')->render()
        ]);
    }

    public function expressLogin(Request $request, $lang)
    {

        if (empty($request->ref)) {
            return response()->json(['message' => 'No reference provided'], 422);
        }

        $this->validate($request, [
            'email' => 'required|string|email|max:255',
            'ref' => 'required|numeric',
        ]);

        $otp = $this->randInt(6);
        
        DB::beginTransaction();
        try {
            ExpressCheckoutOtp::create([
                'email'   => $request->email,
                'otp'   => password_hash($otp, PASSWORD_DEFAULT)
            ]);
    
            Mail::send(new ExpressCheckoutOtpMail($request->email, $otp));
        } catch (\Exception $ex) {
            return response()->json(['message' => 'We could process your request. Try again later.'], 422);
            DB::rollBack();
        }

        DB::commit();

        $email = $request->email;
        $ref = $request->ref;
        $user = User::where('email',$email)->first();
      
        $logs = new TransactionLogs();
        $logs->user_id = $user->id;
        $logs->ref = $request->ref;
        $logs->ip = \Request::ip();
        $logs->user_agent = \Request::server('HTTP_USER_AGENT');
        $logs->referrer = \Request::header('referer') ?? '';
        $logs->page_link = \Request::url();
        $logs->amount = 'N/A';
        $logs->action =  'Express Checkout';
        $logs->message = 'Otp Requested';
        $logs->save();

        return view('merchant.partials.express-otp',compact('email','ref'));
        return response()->json([
            'html' =>  view('merchant.partials.express-otp')->with('email', $request->email)->render()
        ]);

    }

    public function validateExpressLogin(Request $request, $lang) 
    {
        $email = $request->email;
        $otp = $request->otp;
        $ref = $request->ref;
        $amount = $request->amount ?? 0;
        
        if (empty($otp)) {
            return response()->json(['message' => 'No OTP provided'], 422);
        }

        if (empty($ref)) {
            return response()->json(['message' => 'No reference provided'], 422);
        }

        if (empty($email)) {
            return response()->json(['message' => 'No email found'], 422);
        }

        $this->validate($request, [
            'email' => 'required|string|email|max:255',
            'otp' => 'required|integer|digits:6',
            'ref' => 'required|numeric',
        ]);

        $expressOtp = ExpressCheckoutOtp::where('email', $request->email)->latest()->first();

        if (empty($expressOtp)) {
            return response()->json(['message' => 'OTP does not exists, Please generate new OTP',], 422);
        }

        if ($expressOtp->is_expired) {
            return response()->json(['message' => 'OTP expired. Please resend a new one.',], 422);
        }

        if (! password_verify($request->otp, $expressOtp->otp)) {
            return response()->json(['message' => 'OTP does not match',], 422);
        }
        
        DB::beginTransaction();
        try {

            $user = User::where('email', $request->email)->first();

            if (empty($user)) {

                $purchaseRequest = PurchaseRequest::where('ref', $ref)->first();
                $currency = Currency::where('code', $purchaseRequest->currency_code)->first();
                $transferMethod = TransferMethod::where('currency_id', $currency->id)->where('is_system', 1)->first();

                if (empty($transferMethod)) {
                    DB::rollBack();
                    return response()->json(['message' => 'We could not find transfer method related with this currecny. Try again later.'], 422);
                }

                $tempPassword = str_random();
                $username = substr($email, 0, strrpos($email, '@'));

                $user = User::create([
                    'name'  => $username,
                    'first_name'  => $username,
                    'email' =>  $email,
                    'avatar'    => Storage::url('users/default.png'),
                    'password'  =>  bcrypt($tempPassword),
                    'currency_id'	=>	 $currency->id,
                    'is_merchant'   =>  0,
                    'verified'  => 1,
                    'verification_token'  => NULL,
                ]);

                $newwallet = wallet::create([
                    'is_crypto' =>  $currency->is_crypto,
                    'user_id'	=> $user->id,
                    'amount'	=>	0,
                    'currency_id'	=> $currency->id,
                    'accont_identifier_mechanism_value' => 'mimic adress',
                    'transfer_method_id' => $transferMethod->id
                ]);

                $newwallet->TransferMethods()->attach($transferMethod, ['user_id' => $user->id, 'adress' => 'mimic adress']);

                $user->wallet_id = $newwallet->id;
                $user->save();
                
                Mail::send(new ExpressCheckoutRegisterMail($user, $tempPassword));
            }

            Auth::login($user);
            $availableBalance = $this->getAvailableBalance($ref);

        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => 'We could process your request. Try again later.'], 422);
        }
        
        DB::commit();
        
        return view('merchant.partials.balance', compact('availableBalance', 'ref','amount'));

        return response()->json([
            'html' =>  view('merchant.partials.balance', compact('availableBalance', 'ref'))->render()
        ]);
        
    }

    public function logoutFromPayment(Request $request, $lang) 
    {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return back();
    }

    public function refreshBalance($lang, $ref) 
    {

        $logs = new TransactionLogs();
        $logs->user_id = Auth::user()->id;
        $logs->ref = $ref;
        $logs->ip = \Request::ip();
        $logs->user_agent = \Request::server('HTTP_USER_AGENT');
        $logs->referrer = \Request::header('referer') ?? '';
        $logs->page_link = \Request::url();
        $logs->amount = 'N/A';
        $logs->action =  'Refresh Balance';
        $logs->message = 'Balance Refreshed';
        $logs->save();
        return response()->json(['available_balance' =>  $this->getAvailableBalance($ref)], 200);
    }

    public function deposit(Request $request, $lang)
    {
        if($request->ajax()) {
            $depositMethod = DepositMethod::findOrFail($request->id);
            $detail = isset($depositMethod->detail) ? $depositMethod->detail:'';
            $transaction_receipt_ref_no_format = !empty($depositMethod->transaction_receipt_ref_no_format) 
                                                    ? $depositMethod->transaction_receipt_ref_no_format:'';
            $is_eligible = !empty($depositMethod->is_eligible) 
                                                    ? $depositMethod->is_eligible:'';
            return response()->json(['detail'=>$detail, 'transaction_receipt_ref_no_format' => $transaction_receipt_ref_no_format, 'is_eligible' => $is_eligible]);
        }

        $rules = [
            'transaction_receipt_ref_no' =>  'required',
            'amount'    =>  'required',
            'deposit_method_id'    =>  'required',
        ];
        $message = [
            'transaction_receipt_ref_no.required' =>  'The Transaction Reference No field is required.'
        ];
        $request->validate($rules, $message);

        $existingDeposit = Deposit::where('transaction_receipt_ref_no', trim($request->transaction_receipt_ref_no))->first();

        if(!empty($existingDeposit)) {
            if($existingDeposit->transaction_state_id == 3) {
                flash(__('Already submitted deposit request and pending. Please wait for some time.'), 'danger');
                return redirect()->back()->withInput();
            }
            if($existingDeposit->transaction_state_id == 1) {
                flash(__('This Transaction Reference No is already used.'), 'danger');
                return redirect()->back()->withInput();
            }
        }
        $depositMethod = DepositMethod::findOrFail($request->deposit_method_id);
        $currency_id = isset($depositMethod->currency_id) ? $depositMethod->currency_id:'';
        $currency = Currency::findOrFail($currency_id);
        $currency_symbol = isset($currency->symbol) ? $currency->symbol:'';
        $wallet = Wallet::where(['user_id'=>auth()->user()->id])->where(['currency_id'=>$currency_id])->first();
        if(!$wallet)
        {
            flash(__('Wallet not found!'), 'danger');
            // return redirect(route('mydeposits', app()->getLocale()));
            return redirect()->back()->withInput();
        }
        $wallet_id = isset($wallet->id) ? $wallet->id:'';
        $trx = Money::getTrx();

        DB::beginTransaction();
        try {

            $deposit = new Deposit;
            if($request->hasFile('transaction_receipt'))
            {
                $file = $request->file('transaction_receipt');
                $ex = $file->getClientOriginalExtension();
                $filename = time().'.'.$ex;
                $file->move('assets/images', $filename);
                $deposit->transaction_receipt = $filename;
            }
            $deposit->user_id = auth()->user()->id;
            $deposit->unique_transaction_id = $trx;
            $deposit->transaction_receipt_ref_no = isset($request->transaction_receipt_ref_no) ? trim($request->transaction_receipt_ref_no) : null;
            $deposit->net = $request->amount;
            $deposit->gross = $request->amount;
            $deposit->deposit_method_id = $request->deposit_method_id;
            $deposit->currency_id = $currency_id;
            $deposit->currency_symbol = $currency_symbol;
            $deposit->wallet_id = $wallet_id;
            $deposit->transaction_state_id = 3; // pending
            $deposit->save();

            $total_fee = 0;
            $fee_in_amount = Money::getSetting('deposit_fixed_fee');
            $fee_in_percentage = Money::getSetting('deposit_percentage_fee');
            $total_fee_percentage = ($deposit->net/100) * $fee_in_percentage;
            $total_fee = $total_fee_percentage + $fee_in_amount;
            $net_amount = ($deposit->net - $total_fee);

            $purchaseRequest = PurchaseRequest::where('ref', $request->ref)->first();
            $merchant = Merchant::where('merchant_key', $purchaseRequest->merchant_key)->first();

            $deposit->Transactions()->create([
                'user_id' =>  auth()->user()->id,
                'entity_id'   =>  $merchant->id,
                'request_id'=> $purchaseRequest->id, 
                'entity_name' =>  $merchant->name,
                'transaction_state_id'  =>  3, // pending
                'money_flow'    => '+',
                'activity_title'    =>  'Manual Deposit',
                'currency_id' =>  $wallet->currency->id,
                'currency_symbol' =>  $wallet->currency->symbol,
                'thumb' =>  $wallet->currency->thumb,
                'gross' =>  $deposit->net,
                'fee'   =>  $total_fee,
                'net'   =>  $net_amount,
                'balance'   =>  $wallet->fiat,
            ]);

        } catch (\Exception $ex) {
            DB::rollBack();
            flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');

            return back();
        }
        DB::commit();
        
        flash(__('Deposit request submitted successfully! It will be added with your balance after confirmation.'), 'success');

        $refUrl = url('/').'/'.app()->getLocale().'/merchant/storefront/'.$request->ref;
        
        return redirect()->to($refUrl);
    }

    public function logandpay(Request $request, $lang)
    {
        $this->validate($request, [
            'ref' => 'required|numeric',
        ]);

        $purchaseRequest = PurchaseRequest::where('ref', $request->ref)->first();

        
        $merchant = Merchant::with('User')
            ->where('merchant_key', $purchaseRequest->merchant_key)
            ->first();
            
        if ($merchant == null) {
            flash(__('Merchant Not Found.'), 'danger');
            return back();
        }

        $purchaseRequest = PurchaseRequest::where('ref', $request->ref)->first();

        
        $currency = Currency::where('code', $purchaseRequest->currency_code)->first();

        if ($currency->is_crypto == 1) {
            $precision = 8;
        } else {
            $precision = 2;
        }

        $auth_wallet = Auth::user()->walletByCurrencyId($currency->id);

        if (Auth::user()->currentCurrency()->id != $currency->id) {
            $CurrentUser = Auth::user();
            $CurrentUser->currency_id = $currency->id;
            $CurrentUser->save();
            flash(__('Something Went Wrong. Please Try to confirm this payment one more time'), 'danger');
            return back();
        }

        if (is_null($auth_wallet)) {
            //return redirect(app()->getLocale().'/transfer/methods');
            // return redirect(route('show.currencies', app()->getLocale()));

            flash(__('Something Went Wrong. We couldn\'t find your wallet'), 'danger');
            return back();
        }

        if ((bool) $currency == false) {
            flash(__('Wops, something went wrong... looks like we do not support this currency. please contact support if this error persists !'), 'danger');
            return back();
        }

        if ($purchaseRequest->purchaseTransaction != null) {
            flash(__('This purchase request link is already been used. please go back to') . ' <a class="btn btn-xs btn-danger" href="' . $merchant->site_url . '">' . $merchant->name . '</a>' . __('and try to purchase again !'), 'danger');
            return back();
        }

        if ($purchaseRequest == null) {
            flash(__('Woops... Something went wrong !'), 'danger');
            return back();
        }

        if (Auth::user()->account_status == 0) {
            flash(__('Your account is under a withdrawal request review proccess. please wait for a few minutes and try again'), 'info');
            return back();
        }

        if (Auth::user()->id == $merchant->User->id) {
            Auth::logout();

            flash(__('You are logging into the account of the seller for this purchase. Please change your login information and try again.'), 'danger');
            return back();
        }

        if ($auth_wallet->amount < (float) session()->get('PurchaseRequestTotal')) {
            Auth::logout();

            flash(__('You have insufficient funds in your ') . $currency->name . __(' wallet to proceed with this purchase.'), 'danger');
            return back();
        }

        $purchase_fee = 0; //free buy with your site credit

        $sale_fee = bcadd(bcmul('' . $merchant->merchant_percentage_fee / 100, '' . session()->get('PurchaseRequestTotal'), $precision), $merchant->merchant_fixed_fee, $precision);

        if ($currency->is_crypto == 1) {
            $sale_fee = bcmul('' . $merchant->merchant_percentage_fee / 100, '' . session()->get('PurchaseRequestTotal'), $precision);
        }

        $minimum = (float) session()->get('PurchaseRequestTotal') + (float) $sale_fee;

        if ((float) session()->get('PurchaseRequestTotal') - $sale_fee <= 0) {
            flash(__('We only support invoices with a total greater than ') . $minimum . $currency->symbol, 'danger');
            return back();
        }

        // merchant user
        $user = User::find($merchant->User->id);
        $user_wallet = $user->walletByCurrencyId($currency->id);

        if ((bool) $user == false) {
            flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');
            return back();
        }

        DB::beginTransaction();
        try {
            $sale = Sale::create([
                'user_id' => $merchant->User->id,
                'merchant_id' => $merchant->id,
                //'purchase_id' =>  0,
                'transaction_state_id' => 1,
                'gross' => (float) session()->get('PurchaseRequestTotal'),
                'fee' => $sale_fee,
                'net' => bcsub('' . session()->get('PurchaseRequestTotal'), $sale_fee, $precision),
                'currency_id' => $currency->id,
                'currency_symbol' => $currency->symbol,
                'json_data' => json_encode($purchaseRequest->data),
            ]);
    
            if ((bool) $sale == false) {
                DB::rollBack();
                flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');

                return back();
            }
    
            $purchase = Purchase::create([
                'user_id' => Auth::user()->id,
                'merchant_id' => $merchant->id,
                'sale_id' => $sale->id,
                'transaction_state_id' => 1,
                'currency_id' => $currency->id,
                'currency_symbol' => $currency->symbol,
                'gross' => (float) session()->get('PurchaseRequestTotal'),
                'fee' => $purchase_fee,
                'net' => bcsub('' . session()->get('PurchaseRequestTotal'), $purchase_fee, $precision),
                'json_data' => json_encode($purchaseRequest->data),
            ]);
    
            if ((bool) $purchase == false) {
                DB::rollBack();

                flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');
                return back();
            }
    
            $merchant->User->RecentActivity()->save(
                $sale_transaction = $sale->Transactions()->create([
                    'user_id' => $sale->user_id,
                    'entity_id' => $merchant->id,
                    'entity_name' => $merchant->name,
                    'transaction_state_id' => 1, // confirmed
                    'money_flow' => '+',
                    'currency_id' => $currency->id,
                    'currency_symbol' => $currency->symbol,
                    'thumb' => $purchase->User->avatar(),
                    'activity_title' => 'Sale',
                    'gross' => $sale->gross,
                    'fee' => $sale->fee,
                    'net' => $sale->net,
                    'request_id' => $purchaseRequest->id,
                    'json_data' => json_encode($purchaseRequest->data),
                    'balance' => bcadd($user_wallet->amount, $sale->net, $precision),
                ])
            );

            Auth::user()
            ->RecentActivity()
            ->save(
                $transaction = $purchase->Transactions()->create([
                    'user_id' => Auth::user()->id,
                    'entity_id' => $merchant->id,
                    'entity_name' => $merchant->name,
                    'transaction_state_id' => 1, // confirmed
                    'money_flow' => '-',
                    'activity_title' => 'Purchase',
                    'currency_id' => $currency->id,
                    'thumb' => $merchant->logo,
                    'currency_symbol' => $currency->symbol,
                    'gross' => $purchase->gross,
                    'fee' => $purchase->fee,
                    'net' => $purchase->net,
                    'request_id' => $purchaseRequest->id,
                    'json_data' => json_encode($purchaseRequest->data),
                    'balance' => bcsub($auth_wallet->amount, $purchase->net, $precision)
                ])
            );
    
            if ((bool) $transaction == false) {
                DB::rollBack();
                flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');

                return back();
            }
    
            if ((bool) $sale_transaction == false) {
                DB::rollBack();
                flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');

                return back();
            }
    
            $sale->purchase_id = $purchase->id;
            $sale->save();
    
            $auth_wallet->amount = bcsub($auth_wallet->amount, $transaction->net, $precision);
            $auth_wallet->save();
    
            $user_wallet->amount = bcadd($user_wallet->amount, $sale_transaction->net, $precision);
            $user_wallet->save();
    
            $this->sendIpnNotification($transaction);
            
        } catch (\Exception $ex) {
            DB::rollBack();
            flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');

            return back();
        }
        DB::commit();

        $logs = new TransactionLogs();
        $logs->user_id = Auth::user()->id;
        $logs->ref = $request->ref;
        $logs->ip = \Request::ip();
        $logs->user_agent = \Request::server('HTTP_USER_AGENT');
        $logs->referrer = \Request::header('referer') ?? '';
        $logs->page_link = \Request::url();
        $logs->amount = $request->amount ?? '0.00';
        $logs->action =  'Pay Now';
        $logs->message = 'Paid Through Wallet';
        $logs->save();
        
        Auth::logout();

        return redirect(http_build_url($purchaseRequest->data->return_url, ['query' => "token=$purchaseRequest->ref"], HTTP_URL_JOIN_QUERY));
    }

    private function getAvailableBalance($ref) 
    {
        $purchaseRequest = PurchaseRequest::where('ref', $ref)->first();
        $currency = Currency::where('code', $purchaseRequest->currency_code)->first();
        $auth_wallet = Auth::user()->walletByCurrencyId($currency->id);
        return Money::instance()->value($auth_wallet->amount, $currency->symbol, $currency->is_crypto);
    }

    private function sendIpnNotification(Transaction $transaction)
    {
        $merchant = Merchant::find($transaction->entity_id);

        $post = [
            'transaction' => $transaction,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $merchant->success_link);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $response = json_decode(curl_exec($ch), true);

        curl_close($ch);
    }

    private function randInt($digits)
    {
        return rand(pow(10, $digits-1), pow(10, $digits)-1);
    }
}
