<?php

namespace App\Http\Controllers;
use Auth;
use App\Models\Currency;
use App\Models\Wallet;
use App\Models\Voucher;
use App\Models\VoucherOrder;
use Illuminate\Http\Request;

class VoucherController extends Controller
{   

    public function orderForm(Request $request, $lang)
    {   
        $currencies = $this->methods();
        return view('vouchers.orderForm')
        ->with('currencies', $currencies)
        ->with('json', json_encode($currencies));
    }

    public function checkOrder(Request $request){

        $data = $this->validateMethod($request);
        
        //$currency = $data['currency'];
        $method = $data['method'];

        $method['is_crypto'] == 0 ? $p = 2 : $p = 8 ;

       

        $pf = bcdiv(bcmul($request->amount , $method['fees']['percentage'], $p), 100, $p );
        $fee  = bcadd($pf, $method['fees']['fixed'], $p);
        $total = bcadd($request->amount, $fee, $p);

        if($p == 8){
            $fee += 0;
            $total += 0;
        }

        return view('vouchers.orderConfirm')
        ->with('fee', $fee)
        ->with('total', $total)
        ->with('method', $method)
        ->with('amount', $request->amount)
        ->with('currency', $request->currency_code)
        ->with('method', $method);
    }

    public function processOrder(Request $request){
        

        $data = $this->validateMethod($request);
        
        $currency = $data['currency'];
        $method = $data['method'];

  

        $method['is_crypto'] == 0 ? $p = 2 : $p = 8 ;

        $user = Auth::user();
       

        $pf = bcdiv(bcmul($request->amount , $method['fees']['percentage'], $p), 100, $p );
        $fee  = bcadd($pf, $method['fees']['fixed'], $p);
        $total = bcadd($request->amount, $fee, $p);

        $order = VoucherOrder::Create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'amount'    =>  $request->amount,
            'fee'   =>  $fee,
            'total' => $total,
            'is_crypto' => $method['is_crypto'],
            'order_status'  =>  'Pending',
            'payment_method'    =>  $method['name'],
            'out_transaction_id'    =>  0,
            'state' =>  0,
            'vorderid'  => str_random(12), 
        ]);

        $user->cs_token = str_random(12);
        $user->cs_token_added_at = \Carbon\Carbon::now();

        $user->save();

        return \Redirect::away(setting('site.voucher_subdomain').'/pip/'.$user->cs_token .'/'.$order->id);

    }

    public function buyvouchermethod(Request $request, $lang)
    {
        return view('vouchers.purchase_method');
    }
    
    public function getVouchers(Request $request, $lang){
        if(Auth::user()->currentWallet() == null){
            return redirect(route('show.currencies', app()->getLocale()));
        }
    	$vouchers = Voucher::with('User','Loader')->where('user_id', Auth::user()->id)->orwhere('user_loader', Auth::user()->id)->orderby('created_at', 'desc')->paginate(5);
    	$currencies = Currency::where('id' , '!=', Auth::user()->currentCurrency()->id)->get();
    	return view('vouchers.index')
    	->with('currencies', $currencies)
    	->with('vouchers', $vouchers);
    }

    public function loadVoucher(Request $request, $lang){
        if(Auth::user()->currentWallet() == null){
            return redirect(route('show.currencies', app()->getLocale()));
        }
    	$this->validate($request, [
    		'voucher_code'	=>	'exists:vouchers,voucher_code',
    	]);

    	$voucher = Voucher::where('voucher_code', $request->voucher_code)->where('is_loaded', 0)->first();
	    	if ($voucher == null) {
	    		return back();
	    	}
    	$wallet = Wallet::where('currency_id', $voucher->currency_id)->where('user_id', Auth::user()->id)->first();

        
        $currency = Currency::where('id', $voucher->currency_id)->first();
        
        if ( $currency->is_crypto == 1 ){
            $precision = 8 ;
        } else {
            $precision = 2;
        }

        if (is_null($wallet)) {
            $wallet = Wallet::create([
                'user_id'   =>  Auth::user()->id,
                'currency_id'   =>  $voucher->currency_id,
                'is_crypto' =>  $currency->is_crypto
            ]);
        }


    	$wallet->amount = bcadd($wallet->amount , $voucher->voucher_value, $precision ) ;

    	$voucher->user_loader = Auth::user()->id;
    	
    	$voucher->is_loaded = 1 ;

    	$voucher->save();

    	$wallet->save();


    	Auth::user()->RecentActivity()->save($voucher->Transactions()->create([
            'user_id' =>  Auth::user()->id,
            'entity_id'   =>  $voucher->id,
            'entity_name' =>  $wallet->currency->name,
            'transaction_state_id'  =>  1, // waiting confirmation
            'money_flow'    => '+',
            'activity_title'    =>  'Voucher Load',
            'currency_id' =>  $voucher->currency_id,
            'currency_symbol' =>  $voucher->currency_symbol,
            'thumb' =>  $wallet->currency->thumb,
            'gross' =>  $voucher->voucher_value,
            'fee'   =>  0,
            'net'   =>  $voucher->voucher_value,
            'balance'	=>	$wallet->amount,
        ]));

    	flash('Voucher loaded successfully', 'info');
    	return redirect(app()->getLocale().'/home');

    }

    public function createVoucher(Request $request, $lang){
        if(Auth::user()->currentWallet() == null){
            return redirect(route('show.currencies', app()->getLocale()));
        }
    	$this->validate($request, [
    		'voucher_currency'	=>	'exists:currencies,id|numeric',
    		'balance_amount'	=>	'required|numeric'
    	]);

        $wallet = Auth::user()->currentWallet() ;
        $currency = $wallet->currency;

        if ( $currency->is_crypto == 1 ){
            $precision = 8 ;
        } else {
            $precision = 2;
        }

    	
    	$fee = bcadd( bcmul (''.(general_setting('mt_percentage_fee')/100),$request->balance_amount , $precision) , general_setting('mt_fixed_fee'), $precision );

        if ($currency->is_crypto == 1) {
            $fee =  bcmul (''.(general_setting('mt_percentage_fee')/100),$request->balance_amount , $precision);
        }


    	if ($wallet->amount < $fee ) {
    		flash(__('You have insufficient funds to create voucher').' <strong>'.$request->balance_amount.__('to').'generate a voucher'.'</strong>', 'danger');
    		return  back();
    	}

    	if ($wallet->amount < (double)$request->balance_amount ){
    		flash(__('You have insufficient funds to create voucher').' <strong>'.$request->balance_amount.__('to').'generate a voucher'.'</strong>', 'danger');
    		return  back();
    	}

    	
    	$voucher_value = bcsub($request->balance_amount , $fee, $precision ) ;


    	$voucher = Voucher::create([
    		'user_id'	=>	Auth::user()->id,
    		'voucher_amount'	=>	$request->balance_amount,
    		'voucher_fee'	=>	$fee,
    		'voucher_value'	=>	$voucher_value,
    		'voucher_code'	=>	Auth::user()->id.str_random(4).time().str_random(4),
    		'currency_id'	=>	$wallet->currency->id,
    		'currency_symbol'	=>	$wallet->currency->symbol,
    		'wallet_id'	=>	$wallet->id
    	]);
                  dd($voucher);
    	$wallet->amount = bcsub($wallet->amount, $request->balance_amount, $precision ) ;

    	$wallet->save();

    	Auth::user()->RecentActivity()->save($voucher->Transactions()->create([
            'user_id' =>  Auth::user()->id,
            'entity_id'   =>  $voucher->id,
            'entity_name' =>  $wallet->currency->name,
            'transaction_state_id'  =>  1, // waiting confirmation
            'money_flow'    => '-',
            'activity_title'    =>  'Voucher Generation',
            'currency_id' =>  $voucher->currency_id,
            'currency_symbol' =>  $voucher->currency_symbol,
            'thumb' =>  $wallet->currency->thumb,
            'gross' =>  $voucher->voucher_amount,
            'fee'   =>  $voucher->voucher_fee,
            'net'   =>  $voucher->voucher_value,
            'balance'	=>	$wallet->amount,
        ]));

    	flash('Voucher generated successfully', 'info');

    	return back();
    	//validate the wallet balance 
    }

    public function generateVoucher (Request $request, $lang){
        if (Auth::user()->role_id != 1 and Auth::user()->role_id != 3) {
            return back();
        }
        $vouchers = Voucher::with('User','Loader')->where('user_id', Auth::user()->id)->orwhere('user_loader', Auth::user()->id)->orderby('created_at', 'desc')->paginate(5);
        $currencies = Currency::where('id' , '!=', Auth::user()->currentCurrency()->id)->get();
        return view('vouchers.generate')
        ->with('currencies', $currencies)
        ->with('vouchers', $vouchers);

    }

    public function postGenerateVoucher(Request $request, $lang){

        if (Auth::user()->role_id != 1 ) {
            return back();
        }
         

        $this->validate($request, [
            'voucher_currency'  =>  'exists:currencies,id|numeric',
            'balance_amount'    =>  'required|numeric'
        ]);

        $fee = 0;
        
        $wallet = Auth::user()->currentWallet() ;
        $currency = $wallet->Currency;

        if ( $currency->is_crypto == 1 ){
            $precision = 8 ;
        } else {
            $precision = 2;
        }


        $voucher_value = bcsub($request->balance_amount , $fee, $precision );


        

        $voucher = Voucher::create([
            'user_id'   =>  Auth::user()->id,
            'voucher_amount'    =>  $request->balance_amount,
            'voucher_fee'   =>  $fee,
            'voucher_value' =>  $voucher_value,
            'voucher_code'  =>  Auth::user()->id.str_random(4).time().str_random(4),
            'currency_id'   =>  $wallet->currency->id,
            'currency_symbol'   =>  $wallet->currency->symbol,
            'wallet_id' =>  $wallet->id
        ]);


        Auth::user()->RecentActivity()->save($voucher->Transactions()->create([
            'user_id' =>  Auth::user()->id,
            'entity_id'   =>  $voucher->id,
            'entity_name' =>  $wallet->currency->name,
            'transaction_state_id'  =>  1, // waiting confirmation
            'money_flow'    => '↑',
            'activity_title'    =>  'Added Voucher to system',
            'currency_id' =>  $voucher->currency_id,
            'currency_symbol' =>  $voucher->currency_symbol,
            'thumb' =>  $wallet->currency->thumb,
            'gross' =>  $voucher->voucher_amount,
            'fee'   =>  $voucher->voucher_fee,
            'net'   =>  $voucher->voucher_value,
            'balance'   =>  $wallet->amount,
        ]));

        flash('Voucher generated successfully', 'info');

        return back();


    }

public function loadVoucherToUser(Request $request, $lang){

    if (Auth::user()->role_id != 1 and Auth::user()->role_id != 3 ) {
        return back();
    }
     
    $this->validate($request, [
        'voucher_code'  =>  'exists:vouchers,voucher_code',
        'user_name'  =>  'exists:users,name',
    ]);

    $user = \App\User::where('name', $request->user_name)->first();

    $voucher = Voucher::where('voucher_code', $request->voucher_code)->where('is_loaded', 0)->first();
        if ($voucher == null) {
            return back();
        }

    $currency = Currency::where('id', $voucher->currency_id)->first();

    if ( $currency->is_crypto == 1 ){
        $precision = 8 ;
    } else {
        $precision = 2;
    }

    $wallet = Wallet::where('currency_id', $voucher->currency_id)->where('user_id', $user->id)->first();
    
    if (is_null($wallet)) {
        flash('user doesnt have an active wallet in '.$currency->name, 'info');
        return back();
    }


    $wallet->amount = bcadd( $wallet->amount , $voucher->voucher_value, $precision );

    $voucher->user_loader = $user->id;
    
    $voucher->is_loaded = 1 ;

    $voucher->save();

    $wallet->save();


    $user->RecentActivity()->save($voucher->Transactions()->create([
        'user_id' =>  $user->id,
        'entity_id'   =>  $voucher->id,
        'entity_name' =>  $wallet->currency->name,
        'transaction_state_id'  =>  1, //completed
        'money_flow'    => '+',
        'activity_title'    =>  'Voucher Load',
        'currency_id' =>  $voucher->currency_id,
        'currency_symbol' =>  $voucher->currency_symbol,
        'thumb' =>  $wallet->currency->thumb,
        'gross' =>  $voucher->voucher_value,
        'fee'   =>  0,
        'net'   =>  $voucher->voucher_value,
        'balance'   =>  $wallet->amount,
    ]));

    if(Auth::user()->role_id == 1){
        Auth::user()->impersonate($user);
    }    
    flash('voucher loaded successfully ask '. $user->name . ' to check his balance', 'info');
    return redirect(app()->getLocale().'/home');

    }

    public function buyvoucher(Request $request, $lang){
        $user = Auth::user();
        $user->currency_id = 1;
        $user->save();
        return view('vouchers.buy');
    }

    private function methods (){
        return array(
            array(
                'code'   =>   'USD',
                'methods' => array(
                    array(
                        'id'    => 2,
                        'is_crypto' =>  0,
                        'min_deposit'   =>  20,
                        'max_deposit'   =>  1000,
                        'name'  =>  'Stripe',
                        'fees'  =>  array(
                            'fixed' =>  5,
                            'percentage'    =>  2
                        )
                    ),
                    // array(
                    //     'id'    => 3,
                    //     'is_crypto' =>  0,
                    //     'min_deposit'   =>  20,
                    //     'max_deposit'   =>  1000,
                    //     'name'  =>  'Flutterwave',
                    //     'fees'  =>  array(
                    //         'fixed' =>  5,
                    //         'percentage'    =>  2
                    //     )
                    // ),
                )
            ),
        );
    }

    public function validateMethod(Request $request){

         $this->validate($request,[
            'amount' => 'required|numeric',
            'currency_code' => 'required|string|min:3',
            'method'    =>  'required|numeric'
        ]);

        $currencies = $this->methods();
        
        $method = null;
        $currency = null;

        foreach(    $currencies as $curr    ){
            if( $curr['code'] == $request->currency_code ){
                $currency =  $curr['code'];
                foreach ($curr['methods'] as $method) {
                    if($method['id'] == $request->method){
                        $method  = $method ;
                        break;
                    }
                }
            }
        }

        if(is_null($method)){
            abort(404);
        }

        if($currency == null){
            abort(404);
        }

        if($request->amount < $method['min_deposit'] or $request->amount > $method['max_deposit']){
            abort(404);
        }

        $currency = Currency::where('code', $currency)->first();

        if($currency == null){
            abort(404);
        }

        $data = [
            'method'    => $method,
            'currency' => $currency
        ];

        return $data;

    }

}
