<?php

namespace App\Http\Controllers;

use App\Helpers\Money;
use App\Mail\ExpressCheckoutRegisterMail;
use Intervention\Image\Facades\Image;
use App\Models\Merchant;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\DepositMethod;
use App\Models\PurchaseRequest;
use App\Models\Transaction;
use App\Models\TransferMethod;
use App\Models\Wallet;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\TransactionLogs;


class MerchantController extends Controller
{
    public function index(Request $request, $lang){
        if(Auth::user()->currentWallet() == null){
            return redirect(route('show.currencies', app()->getLocale()));
        }
    	$merchants = Merchant::where('user_id', Auth::user()->id)->orderby('created_at', 'desc')->paginate(5);
    	return 	view('merchant.index')
    			->with('merchants', $merchants);
    }

    public function new(Request $request, $lang){
        if(Auth::user()->currentWallet() == null){
            return redirect(route('show.currencies', app()->getLocale()));
        }
        $currencies = Currency::all();
    	return 	view('merchant.new')
        ->with('currencies', $currencies);
    }

    public function integration(Request $request, $lang, Merchant $merchant){
        if(Auth::user()->currentWallet() == null){
            return redirect(route('show.currencies', app()->getLocale()));
        }
        if ( $merchant and $merchant->user_id == Auth::user()->id) {
            return view('merchant.docs')
            ->with('merchant', $merchant)
            ->with('merchantArray', $merchant->toArray());
        }
        return back();
    }

    public function add(Request $request, $lang){

        $regex = "/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/";

    	$this->validate($request, [
    		// 'merchant_name'	=> 'required|unique:merchants,name',
    		'merchant_site_url'	=>	'required|regex:'.$regex,
            'merchant_currency' =>  'required|exists:currencies,id',
            'merchant_logo' =>  'nullable|image',
    		'merchant_success_link'	=>	'required|regex:'.$regex,
    		'merchant_fail_link'	=>	'required|regex:'.$regex,
            'merchant_ipn_link'    =>  'required|regex:'.$regex,
    		'merchant_description'	=>	'required',
            'logo'  =>  'required|mimes:jpeg,jpg,png'
    	]);

        $file = $request->file('logo');

        $filename = hash('sha1',$file->getClientOriginalName().'-'.time()).'.'.$file->getClientOriginalExtension() ;

        $filePath = 'merchants/'. Auth::user()->name.'/'.$filename;

        $image = Image::make($file);
        $image->fit(200, 200);

        

        if (Storage::put($filePath, (string) $image->encode())) {
            //TODO        
        }else{
            return back();
        }

        $currency = Currency::findorFail($request->merchant_currency);


    	$merchant = new Merchant();
    	$merchant->user_id = Auth::user()->id;
        $merchant->logo = Storage::url($filePath);
    	$merchant->name = $request->merchant_name;
        $merchant->currency_id = $currency->id;
    	$merchant->site_url = $request->merchant_site_url;
        $merchant->ipn_url =  $request->merchant_ipn_link;
    	$merchant->success_link = $request->merchant_success_link;
    	$merchant->fail_link = $request->merchant_fail_link;
    	$merchant->description = $request->merchant_description;
    	$merchant->merchant_key = bcrypt(env('APP_KEY').now().Auth::user()->id);
    	$merchant->save();

    	return redirect(route('mymerchants', app()->getLocale()));
    }

    public function storefront(Request $request, $lang){
        
        if(Auth::check())
            Auth::logout();

        if ($request->has('merchant_key') and $request->has('invoice')) {

            $invoice = json_decode($request->invoice , true);

            $merchant = Merchant::where('merchant_key', $request->merchant_key)->first();

            session()->put('merchant_key', $request->merchant_key);
            session()->put('merchant_key', $request->merchant_key);
            session()->put('sumary', $request->sumary);
            session()->put('item_name', $request->item_name);
            session()->put('amount', $request->amount);
            session()->put('invoice', $invoice);

            if($merchant){
                return  
                    view('merchant.storefront')
                    ->with('merchant', $merchant);   
            }

        }

        abort(404);
    }

    // temporarily deprecated by getPaymentStorefront
    public function getStoreFront(Request $request, $lang, $ref){
        // if(Auth::check())
        //     Auth::logout();

        $PurchaseRequest = PurchaseRequest::with('Transaction')->with('Currency')->where('ref', $ref)->first();

        if($PurchaseRequest == null)
        return abort(404); 

        if($PurchaseRequest->attempts >= 5 ){
            if( $PurchaseRequest->is_expired == false ){
                $PurchaseRequest->is_expired = true ;
                $PurchaseRequest->save();
            }

            return abort(404); 
        }

        if(($PurchaseRequest == null || $PurchaseRequest->is_expired == true) && !session()->has('PurchaseRequest')) {

            return abort(404); 
        }

        
        $total = 0;

        $merchant = Merchant::where('merchant_key', $PurchaseRequest->merchant_key)->first();
        
        if($merchant == null) {
            return abort(404);
        }
            
        foreach ($PurchaseRequest->data->items as $item) {
            $total += ( $item->price * $item->qty );
        }

        $availableBalance = '';
        if (Auth::check()) {
            $currency = Currency::where('code', $PurchaseRequest->currency_code)->first();
            $auth_wallet = Auth::user()->walletByCurrencyId($currency->id);
            $availableBalance = Money::instance()->value($auth_wallet->amount, $currency->symbol, $currency->is_crypto);
        }

        session()->put('PurchaseRequest', $PurchaseRequest);
        session()->put('PurchaseRequestTotal', $total);

        // $PurchaseRequest->attempts ++;
        // $PurchaseRequest->save();
        
        $depositMethod = DepositMethod::whereStatus(1)->orderBy('sequence_no', 'asc')->get();

        return view('merchant.storefront', compact('ref', 'merchant', 'availableBalance', 'depositMethod'));
    }



    public function getPaymentStorefront(Request $request, $lang, $ref)
    {
        if(Auth::check())
            Auth::logout();

        $purchaseRequest = PurchaseRequest::with('Transaction')->with('Currency')->where('ref', $ref)->first();
        
        if ( empty($purchaseRequest) ) {

            flash(__('Error!! No request found.'), 'danger');
            abort(404);
        }

        if ( $purchaseRequest->is_expired ) {
            flash(__('Error!! request expired.'), 'danger');
            abort(404);
        }
        
        if ($purchaseRequest->attempts >= 5 ) {

            if( $purchaseRequest->is_expired == false ){
                $purchaseRequest->is_expired = true ;
                $purchaseRequest->save();
            }

            flash(__('Error!! too many attempt.'), 'danger');
            abort(429);
        }

        $merchantKey = $purchaseRequest->merchant_key;

        $merchant = Merchant::where('merchant_key', $merchantKey)->first();

        if (empty($merchant)) {
            flash(__('Error!! no merchant found.'), 'danger');
            abort(404);
        }


        if ($merchant->status == 'Approved') {
            $url = $merchant->site_url;
            $site_url = parse_url($url, PHP_URL_HOST);
            $host = parse_url(request()->headers->get('referer'), PHP_URL_HOST);

            if ($site_url != $host) {
                flash(__('Error!! Requested url does not match with the merchant site url.'), 'danger');
                abort(404);
            }
        }

        if ($merchant->status == 'Rejected') {
            flash(__('Error!! Merchant Rejected!.'), 'danger');
            abort(404);
        }

        $loginRequired = $purchaseRequest->login_required;
        $email = $purchaseRequest->email;
        $user = User::where('email', $email)->first();

        $currency = Currency::where('code', $purchaseRequest->currency_code)->first();
        $transferMethod = TransferMethod::where('currency_id', $currency->id)->where('is_system', 1)->first();
        $merchantRedirect = http_build_url($purchaseRequest->data->return_url, ['query' => "token=$purchaseRequest->ref"], HTTP_URL_JOIN_QUERY);

        DB::beginTransaction();
        try {
            $tempPassword = null;
            if (empty($transferMethod)) {
                $transferMethod  = TransferMethod::create([
                    'currency_id' => $currency->id,
                    'name' => 'System_'.$currency->name.'_method',
                    'accont_identifier_mechanism' => 'placeholder',
                    'how_to_deposit' => 'placeholder',
                    'how_to_withdraw' => 'placeholder',
                    'days_to_process_transfer' => 1,
                    'is_active' => 0,
                    'thumbnail' => 'placeholder',
                    'deposit_percentage_fee' => Money::getSetting('deposit_percentage_fee'),
                    'deposit_fixed_fee' => Money::getSetting('deposit_fixed_fee'),
                    'withdraw_percentage_fee' => Money::getSetting('withdraw_percentage_fee'),
                    'withdraw_fixed_fee' => Money::getSetting('withdraw_fixed_fee'),
                    'mobile_thumbnail' => 'placeholder',
                    'merchant_percentage_fee' => Money::getSetting('merchant_percentage_fee'),
                    'merchant_fixed_fee' => Money::getSetting('merchant_fixed_fee'),
                    'is_hidden' => 0,
                    'is_system' => 1
                ]);
            }
            
            if (empty($user)) {
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
                    'white_label_status' => 0,
                    'payout_status' => 0,
                ]);

                $wallet = wallet::create([
                    'is_crypto' =>  $currency->is_crypto,
                    'user_id'	=> $user->id,
                    'amount'	=>	0,
                    'currency_id'	=> $currency->id,
                    'accont_identifier_mechanism_value' => 'mimic adress',
                    'transfer_method_id' => $transferMethod->id
                ]);
                
                $wallet->TransferMethods()->attach($transferMethod, ['user_id' => $user->id, 'adress' => 'mimic adress']);
                
                $user->wallet_id = $wallet->id;
                $user->save();
                
            } else {

                $wallet = Wallet::where('user_id', $user->id)->where('currency_id', $currency->id)->first();

                if(empty($wallet)) {
                    $wallet = wallet::create([
                        'is_crypto' =>  $currency->is_crypto,
                        'user_id'	=> $user->id,
                        'amount'	=>	0,
                        'currency_id'	=> $currency->id,
                        'accont_identifier_mechanism_value' => 'mimic adress',
                        'transfer_method_id' => $transferMethod->id
                    ]);
    
                    $wallet->TransferMethods()->attach($transferMethod, ['user_id' => $user->id, 'adress' => 'mimic adress']);
    
                    $user->wallet_id = $wallet->id;
                    $user->save();
                }
            }
            
            
            $total = 0;
            foreach ($purchaseRequest->data->items as $item) {
                $total += ( $item->price * $item->qty );
            }
            
            $depositMethodId = config('app.static_merchant_payment_deposit_method_id'); // UPI hard coded. Bad design choice
            $trx = Money::getTrx();

            $transaction = Transaction::query()
                ->where('transactionable_type', Deposit::class)
                ->where('request_id', $purchaseRequest->id)
                ->first();
            $deposit = null;
            if (!empty($transaction)) {
                $deposit = Deposit::find($transaction->transactionable_id);
            }

            if(empty($deposit)) {
                $deposit = new Deposit();
                $deposit->user_id = $user->id;
                $deposit->unique_transaction_id = $trx;
                $deposit->transaction_receipt_ref_no = $trx;
                $deposit->net = $total;
                $deposit->gross = $total;
                $deposit->deposit_method_id = $depositMethodId;
                $deposit->currency_id = $currency->id;
                $deposit->currency_symbol = $currency->symbol;
                $deposit->wallet_id = $wallet->id;
                $deposit->request_id = $purchaseRequest->id;
                $deposit->transaction_state_id = 3; // pending
                $deposit->save();

                
                $total_fee = 0;
                $fee_in_amount = Money::getSetting('deposit_fixed_fee');
                $fee_in_percentage = Money::getSetting('deposit_percentage_fee');
                $total_fee_percentage = ($deposit->net/100) * $fee_in_percentage;
                $total_fee = $total_fee_percentage + $fee_in_amount;
                $net_amount = ($deposit->net - $total_fee);

                $transaction = $deposit->Transactions()->create([
                    'user_id' =>  $user->id,
                    'entity_id'   =>  $merchant->id,
                    'request_id'=> $purchaseRequest->id, 
                    'entity_name' =>  $merchant->name,
                    'transaction_state_id'  =>  3, // pending
                    'money_flow'    => '+',
                    'activity_title'    =>  'Manual Deposit',
                    'currency_id' =>  $currency->id,
                    'currency_symbol' =>  $currency->symbol,
                    'thumb' =>  $currency->thumb,
                    'gross' =>  $deposit->net,
                    'fee'   =>  $total_fee,
                    'net'   =>  $net_amount,
                    'balance'   =>  $wallet->fiat,
                ]);
            }

            // qr code service
            $url = 'https://ap-south-1.aws.data.mongodb-api.com/app/application-0-arvdg/endpoint/txn_qr';
            $headers = [
                'api-key: swf3DejHuSNcEgLxPxk7ks6oR1DZFSus85Nm3CGpjluY52UxNCEBZxHh8UXJfnRE',
                'Content-Type: application/json'
            ];
            $data = [
                'amount' => $total,
                'emailId' => $email,
                'merchantId' => $merchantKey,
                'token' =>$ref

            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,            $url );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($ch, CURLOPT_POST,           true );
            curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode($data) ); 
            curl_setopt($ch, CURLOPT_HTTPHEADER,     $headers); 

            
            $result = json_decode(curl_exec($ch), true);

            // $result = [
            //     "qr" => "data:image/png;base64,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",
            //     "sourceAmount" => 5,
            //     "showAmount" => 4.52,
            //     "name" => "bharatpe",
            //     "type" => "aggregator",
            //     "vpa" => "bharatpe.8001192182@fbpe",
            //     "emailId" => "check@email.com",
            //     "merchantId" => "$2y$10$4CZ.PCSMrECD0/Gwre0uN.HTAeSYvmt0TLoI3sbGYTvUuWQCsqrLy",
            //     "status" => "pending",
            //     "createdAt" => "2023-11-15T03:56:36.867Z",
            //     "qrId" => "655441743d9e589365e27195",
            //     "gqrs" => [
            //         0 => 5
            //     ]
            // ];
            $qrServicePayload = array_except($result, ['gqrs']);
            
            $deposit->qr_service_payload = json_encode($qrServicePayload);
            $deposit->save();
            
            $qr = $result['qr'];
            $showAmount = $result['showAmount'];
            $sourceAmount = $result['sourceAmount'];
            
            if (!empty($tempPassword)) {
                Mail::send(new ExpressCheckoutRegisterMail($user, $tempPassword));
            }

        } catch (\Exception $ex) {
            DB::rollBack();
            flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');
            abort(400);
        }

        DB::commit();

        $pageRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) &&($_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ||  $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache'); 

        $userId = $user->id;
        $email = $user->email ?? '';

        $logs = new TransactionLogs();
        $logs->user_id = $userId;
        $logs->ref = $ref;
        $logs->ip = \Request::ip();
        $logs->user_agent = \Request::server('HTTP_USER_AGENT');
        $logs->referrer = \Request::header('referer') ?? '';
        $logs->page_link = \Request::url();
        $logs->amount = $sourceAmount ?? '0.00';
        $logs->action =  $pageRefreshed ? 'Page Refreshed' : 'Link Generated';
        $logs->message = $pageRefreshed ? 'Page Refreshed After Link Generation' :'Link Generation Completed';
        $logs->save();

        return view('merchant-payment.payment', compact('ref', 'sourceAmount', 'showAmount', 'qr', 'merchantKey', 'merchantRedirect', 'merchant', 'loginRequired','userId','email'));
        
        // if ($request->query('mode') && $request->query('mode') === 'wire') {
        //     return view('merchant-payment.wire_payment', compact('ref', 'sourceAmount', 'showAmount', 'merchant'));
        // }else{
        //     return view('merchant-payment.payment', compact('ref', 'sourceAmount', 'showAmount', 'qr', 'merchantKey', 'merchantRedirect', 'merchant', 'loginRequired','userId','email'));
        // }
    }


    public function depositFromPayment(Request $request, $lang)
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

        $purchaseRequest = PurchaseRequest::where('ref', $request->ref)->first();

        $merchant = Merchant::with('User')
        ->where('merchant_key', $purchaseRequest->merchant_key)
        ->first();

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
        $payment_method_id = DepositMethod::findOrFail($request->deposit_method_id);


        $currency_id = isset($payment_method_id->currency_id) ? $payment_method_id->currency_id: '';
        $currency = Currency::findOrFail($currency_id);
        $currency_symbol = isset($currency->symbol) ? $currency->symbol:'';
        $wallet = Wallet::where(['user_id'=>auth()->user()->id])->where(['currency_id'=>$currency_id])->first();

        if(!$wallet)
        {
            flash(__('Wallet not found!'), 'danger');
            return redirect()->back()->withInput();
        }

        $wallet_id = isset($wallet->id) ? $wallet->id:'';
        $trx = Money::getTrx();

        DB::beginTransaction();
        try {

            $deposit = new Deposit();
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
            $deposit->transaction_receipt_ref_no = $trx;

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

            $deposit->Transactions()->create([
                'user_id' =>  auth()->user()->id,
                'entity_id'   =>  $merchant->id,
                'request_id'=> $purchaseRequest->id, 
                'transactionable_id'=> $deposit->id,
                'transactionable_type'=> Deposit::class,
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

        $refUrl = url('/').'/'.app()->getLocale().'/merchant/payment-storefront/'.$request->ref;
        
        return redirect()->to($refUrl);
    }


}
