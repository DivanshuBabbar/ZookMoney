<?php
namespace App\Http\Controllers;
use Auth;
use \App\User;
use \App\Models\Wallet;
use Illuminate\Http\Request;
use \App\Models\VirtualCard;
use \App\Models\VirtualCardDetail;
use \App\Models\PaymentLink;
use \App\Models\PaymentLinkOrder;
use \Stripe\Stripe;
class PaymentLinkController extends Controller
{
    public function list(Request $request, $lang){
      $paymentlinks = PaymentLink::with('currency')->where('user_id', Auth::user()->id)->orderby('id', 'desc')->paginate(9);
    	return view('paymentlinks.index')->with('paymentlinks', $paymentlinks);
    }
    public function createPaymentLink(Request $request){
      
      $this->validate($request,[
        'name' => 'required',
        'amount' => 'numeric|required',
        'description' =>'required',
      ]);
      $wallet = Auth::user()->currentWallet();
  
      if($wallet->is_crypto == 0){
         if( $request->amount <= 0.0001 ){
              flash(__('the minimum amount is 10 for ' . $wallet->currency->name), 'danger');
          return back();
          }
          
      }
      if($wallet->is_crypto == 1){
         flash(__('this feature is only available for Fiat currency '),'danger');
          return back();
      }
      $paymentlink = PaymentLink::create([
        'name' => $request->name,
        'user_id' => Auth::user()->id,
        'paymentlink_id' => uniqid(),
        'currency_id' => $wallet->currency_id,
        'amount'  =>  $request->amount,
        'paymentlink_details' => $request->description,
        'is_crypto' => $wallet->is_crypto,
        'payment_status' => 1,
        'link_status' => 1,
      ]);
      flash(__('Paymeny link created successfully'), 'info');
      return back();
    }
    public function paymentStoreFront(Request $request, $lang, $payment_id)
    {
      if(session()->has('gateway_callback_url'))
      {
        session()->forget('gateway_callback_url');
      }
      if(session()->has('redirect_url'))
      {
        session()->forget('redirect_url');
      }
      if(session()->has('link_owner_id'))
      {
        session()->forget('link_owner_id');
      }
      $paymentlink = PaymentLink::where('paymentlink_id', $payment_id)->first();
      $paymentlink ?? abort(404);
      if(Auth::check())
      {
        if(Auth::user()->id == $paymentlink->user_id)
        {
          return view('paymentlinks.storefront')->with('paymentlink', $paymentlink);
        }
        Auth::logout();
      }
      return view('paymentlinks.storefront')->with('paymentlink', $paymentlink);
    }
    public function payWithWalletBalance(Request $request){
      $this->validate($request, [
        'pid' => 'required',
        'email' => 'required',
        'password' => 'required',
      ]);
      $paymentlink = PaymentLink::where('paymentlink_id', $request->pid)->first();
      if ($paymentlink == null) {
        flash('something went wrong.', 'danger');
        return back();
      }
      $link_owner = User::where('id', $paymentlink->user_id)->first();
     
      if($link_owner == null){
        flash('something went wrong.', 'danger');
        return back();
      }
      $link_owner_wallet = Wallet::where('user_id', $link_owner->id)->where('currency_id', $paymentlink->currency->id)->first();
      if($link_owner_wallet == null){
        flash('something went wrong.', 'danger');
            dd('owner wallet');
        return back();
      }
      if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
           if($link_owner->id == Auth::user()->id){
              flash('trying to pay your own link is not allowed', 'danger');
              return back();
            }
           
            
          $auth_wallet = Wallet::where('user_id', Auth::user()->id)->where('currency_id', $paymentlink->currency_id)->first();
           if($auth_wallet == null){
              flash('something went wrong.', 'danger');
              dd('auth_wallet');
              return back();
            }
            if($auth_wallet->amount <= $paymentlink->amount){
               flash('Insufficient funds in your '. $paymentlink->currency->name . ' wallet ', 'danger');
              return back();
            }
            if ( $paymentlink->currency->is_crypto == 1 ){
                $precision = 8 ;
            } else {
                $precision = 2;
            }
            // Add a fee and the link owner pays the fee;
            $paymentlink_fee = 0;
            $amount_to_add = bcsub($paymentlink->amount, $paymentlink_fee, $precision);
       
            //add amount to owner wallet
            $link_owner_wallet->amount = bcadd(  $link_owner_wallet->amount,$amount_to_add, $precision);  
            //remove amount from payer wallet
            $auth_wallet->amount = bcsub($auth_wallet->amount, $amount_to_add, $precision);
            $paymentLinkOrder = PaymentLinkOrder::create([
              'paymentlink_id' => $paymentlink->id ,
              'paymentlink_reference' => $paymentlink->paymentlink_id,
              'link_paid_by_platform' => general_setting('site_name'),
              'email' => Auth::user()->email,
              'order_status' => 'unpaid',
            ]); 
            $auth_wallet->save();
            $link_owner_wallet->save();
            $link_owner->RecentActivity()->save($paymentLinkOrder->Transactions()->create([
                'user_id' => $link_owner->id,
                'entity_id'   =>  Auth::user()->id,
                'entity_name' =>  Auth::user()->email,
                'transaction_state_id'  =>  1, // waiting confirmation
                'money_flow'    => '+',
                'balance' =>  $link_owner_wallet->amount,
                'currency_id' =>  $paymentlink->currency->id,
                'currency_symbol' =>  $paymentlink->currency->symbol,
                'thumb' =>  Auth::user()->avatar(),
                'activity_title'    =>  'Payment Link',
                'gross' =>  $paymentlink->amount,
                'fee'   =>  $paymentlink_fee,
                'net'   =>  bcsub($paymentlink->amount, $paymentlink_fee, $precision),
                'json_data' =>  NULL,
            ]));
            Auth::user()->RecentActivity()->save($paymentLinkOrder->Transactions()->create([
                'user_id' => Auth::user()->id,
                'entity_id'   =>  $link_owner->id,
                'entity_name' =>  $link_owner->email,
                'transaction_state_id'  =>  1, // waiting confirmation
                'money_flow'    => '-',
                'currency_id' =>  $paymentlink->currency->id,
                'currency_symbol' =>  $paymentlink->currency->symbol,
                'thumb' => $link_owner->avatar(),
                'activity_title'    =>  'Payment Link',
                'gross' =>  $paymentlink->amount,
                'fee'   =>  $paymentlink_fee,
                'net'   =>  $paymentlink->amount,
                'balance' => $auth_wallet->amount, 
                'json_data' =>  NULL,
            ]));
            $paymentLinkOrder->order_state = 1;
            $paymentLinkOrder->order_status = 'paid';
            $paymentLinkOrder->save();
          
         //  //Send otp Mail
         //  Mail::send(new otpEmail( $user->email, $generated_otp));
         //  //Send otp SMS
         // // Twilio::message($user->phonenumber, array(
         // //      'body' => __('Your ') . general_setting('site_name') . __(' one time password (OTP) is :  ') . $generated_otp . __('   Do not share this code with others.'),
         // //      'SERVICE SID'  =>  'Envato',
         // //  ));
          
          //
          
        flash('Payment made successfully', 'success');
        
        return redirect(app()->getLocale().'/home');
      }
      else{
            flash('We couldn\'t find this user in our systen', 'danger');
            return back();
      }
    }
    public function payWithCard(Request $request){
      
      $this->validate($request,[
        'cardToken' => 'required',
        'email' =>  'required',
        'link' =>  'required',
      ]);
       $paymentlink = PaymentLink::where('paymentlink_id', $request->link)->first();
      if ($paymentlink == null) {
        flash('something went wrong.', 'danger');
        return back();
      }
      $link_owner = User::where('id', $paymentlink->user_id)->first();
     
      if($link_owner == null){
        flash('something went wrong.', 'danger');
        return back();
      }
      $link_owner_wallet = Wallet::where('user_id', $link_owner->id)->where('currency_id', $paymentlink->currency->id)->first();
      if($link_owner_wallet == null){
        flash('something went wrong.', 'danger');
            dd('owner wallet');
        return back();
      }
      if ( $paymentlink->currency->is_crypto == 1 )
      {
          $precision = 8 ;
      } else {
          $precision = 2;
      }      
      Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
      // Stipe fee 3.99% + R$0.50;
      
        try{
          \Stripe\Charge::create([
            'amount'  =>  $paymentlink->amount * 100,
            'currency'  =>  $paymentlink->currency->code,
            'source'  =>  $request->cardToken,
            'description' =>  $paymentlink->paymentlink_details,
        ]);
        // Add a fee and the link owner pays the fee;
        $paymentlink_fee = 0;
        $amount_to_add = bcsub($paymentlink->amount, $paymentlink_fee, $precision);
   
        //Create Payment Link order
         $paymentLinkOrder = PaymentLinkOrder::create([
            'paymentlink_id' => $paymentlink->id ,
            'paymentlink_reference' => $paymentlink->paymentlink_id,
            'link_paid_by_platform' => 'Stripe Credit Card',
            'email' =>$request->email,
            'order_status' => 'paid',
        ]); 
        //add credit to user wallet.
        $link_owner_wallet->amount = bcadd(  $link_owner_wallet->amount,$amount_to_add, $precision); 
        // Create Transaction
        $link_owner->RecentActivity()->save($paymentLinkOrder->Transactions()->create([
              'user_id' => $link_owner->id,
              'entity_id'   =>  $link_owner->id,
              'entity_name' =>  $request->email,
              'transaction_state_id'  =>  1, // waiting confirmation
              'money_flow'    => '+',
              'currency_id' =>  $paymentlink->currency->id,
              'currency_symbol' =>  $paymentlink->currency->symbol,
              'balance' =>   $link_owner_wallet->amount,
              'thumb' =>  $link_owner->avatar(),
              'activity_title'    =>  'Payment Link',
              'gross' =>  $paymentlink->amount,
              'fee'   =>  $paymentlink_fee,
              'net'   =>  bcsub($paymentlink->amount, $paymentlink_fee, $precision),
              'json_data' =>  NULL,
        ]));
        $paymentLinkOrder->order_state = 1;
        $paymentLinkOrder->order_status = 'paid';
        $paymentLinkOrder->save();
        $link_owner_wallet->save();
        //TODO
        //Send Notification to  email.
        //TODO
        //Send email notification for user.
        flash('You\'ve paid this link successfully .' ,'success');
        return back();
      }catch(\Exception $e){
        dd($e);
      }
    }
}
