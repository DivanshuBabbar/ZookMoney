<?php
namespace App\Http\Controllers;
use Auth;
use App\Models\Escrow;
use App\User;
use Twilio;
use App\Models\Otp;
use App\Models\Receive;
use App\Models\Transaction;
use App\Models\Currrency;
use App\Models\Country;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use TCG\Voyager\Models\Page;
use Jenssegers\Agent\Agent;
use Mail;
use App\Mail\GiftCardEmail;
class GiftCardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $gift_card_fee_percentage,$admin_email;
    public function __construct()
    {
        $general_settings = GeneralSetting::first();
        $fee = isset($general_settings->gift_card_fee) ? $general_settings->gift_card_fee:0;
        $admin_email = isset($general_settings->support_email) ? $general_settings->support_email:'';
        $this->gift_card_fee_percentage = $fee;
        $this->admin_email = $admin_email;
    }
    public function gift_card()
    {
        $countries=country::get();
        $fee = $this->gift_card_fee_percentage;
        return view('giftcard.index',compact('countries','fee'));
    }
    public function order_gift_card(Request $request)
    {
    	$wallet = Auth::user()->currentWallet();
        $minBalance = $wallet->amount;
        $this->validate($request,[
        	'country_id'=>'required',
        	'card_id'=>'required',
        	'price'=>'required',
        	'qty'=>'required',
        ]);
        $price = $request->price;
        $qty = 1;
        if($qty <= 0)
        {
        	flash('Quantity should be greater 0', 'danger');
	    	return back();
        }
        // $price = ($qty * $price);
        $card_id = $request->card_id;
        $country_id = $request->country_id;
        $country_detail = Country::find($country_id);
        $country_code = isset($country_detail->code) ? $country_detail->code:'';
        $fee = $this->gift_card_fee_percentage;
        $fee_amount = ($price/100) * $fee;
        $total_amount = ($price + $fee_amount);
        if($total_amount < 0)
        {
        	flash('Amount should be greater 0', 'danger');
	    	return back();
        }
        if($total_amount > $minBalance)
        {
        	flash('You have low balance', 'danger');
	    	  return back();
        }
        $email = auth()->user()->email;
        $username = auth()->user()->name;
        $name = auth()->user()->name;
        $post_data = [];
        $post_data['productId'] = $card_id;
        $post_data['countryCode'] = $country_code;
        $post_data['quantity'] = $qty;
        $post_data['unitPrice'] = $price;
        $post_data['customIdentifier'] = $request->customer_name;
        $post_data['senderName'] = $name;
        $post_data['recipientEmail'] = $this->admin_email;
        // $post_data['recipientPhoneDetails'] = array('countryCode'=>$country_code,'')
        $post_data = json_encode($post_data);
        $accessToken = $this->getAccessToken();
    		$curl = curl_init();
    		curl_setopt_array($curl, array(
    		  CURLOPT_URL => GIFT_CARD_API_URL.'/orders',
    		  CURLOPT_RETURNTRANSFER => true,
    		  CURLOPT_ENCODING => '',
    		  CURLOPT_MAXREDIRS => 10,
    		  CURLOPT_TIMEOUT => 0,
    		  CURLOPT_FOLLOWLOCATION => true,
    		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    		  CURLOPT_CUSTOMREQUEST => 'POST',
    		  CURLOPT_POSTFIELDS =>$post_data,
    		  CURLOPT_HTTPHEADER => array(
    		    'Authorization: Bearer '.$accessToken,
    		    'Content-Type: application/json',
    		    'Accept: application/com.reloadly.giftcards-v1+json'
    		  ),
    		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response);
    // echo '<pre>',print_r($response),'</pre>';exit();
		if(isset($response->status) && $response->status == 'SUCCESSFUL')
		{
            $getRedeemCode = $this->getRedeemCode($response->transactionId);
            $cardNumber = isset($getRedeemCode[0]->cardNumber) ? $getRedeemCode[0]->cardNumber:'';
            $pinCode = isset($getRedeemCode[0]->pinCode) ? $getRedeemCode[0]->pinCode:'';
			$wallet->amount = ($wallet->amount - $total_amount);
            $wallet->save();
    			Transaction::create([
                'user_id' =>  Auth::user()->id,
                'entity_id'   =>  $card_id,
                'transactionable_id'=>isset($response->transactionId) ? $response->transactionId:'',
                'entity_name' =>  $wallet->currency->name,
                'transaction_state_id'  =>  1, // waiting confirmation
                'money_flow'    => '-',
                'activity_title'    =>  'Gift Card',
                'currency_id' =>  $wallet->currency->id,
                'currency_symbol' =>  $wallet->currency->symbol,
                'thumb' =>  $wallet->currency->thumb,
                'gross' =>  $price,
                'fee'   =>  $fee_amount,
                'net'   =>  $total_amount,
                'balance'	=>	$wallet->amount,
            ]);
        $message = 'Card purchased successfully';
        $message.='<br>';
        $message.='cardNumber is: '.$cardNumber;
        $message.='<br>';
        $message.='pinCode is: '.$pinCode;
        $user = auth()->user();
        Mail::send(new GiftCardEmail($user->email,$cardNumber,$pinCode));
	    	flash($message, 'info');
	    	return back();
		}
		else
		{
			$message = isset($response->message) ? $response->message:'';
			flash($message, 'danger');
	    	return back();
		}
    }
    public function getGiftCards($lange = null,$code = null,$country_id = null,$product_id = null)
    {
        $giftcards = '';
        $price_detail='';
        if($code!=null)
        {
            $giftcards = $this->getGiftCardsAccordingToCode($code);
        }
        if($product_id!=null)
        {
            $product_detail = $this->getGiftCardsById($product_id);
            $price_detail = isset($product_detail->fixedRecipientDenominations) ? $product_detail->fixedRecipientDenominations:'';
            if(empty($price_detail))
            {
            	flash('This Gift Card not available at this moment', 'danger');
	    		return redirect(url('/giftCards'));
            }
        }
        $countries=country::get();
        $fee = $this->gift_card_fee_percentage;
        return view('giftcard.index',compact('countries','giftcards','country_id','product_id','price_detail','fee'));
    }
    private function getRedeemCode($transaction_id = null)
    {
        $data = [];
        if($transaction_id!=null)
        {
            $accessToken = $this->getAccessToken();
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => GIFT_CARD_API_URL.'/orders/transactions/'.$transaction_id.'/cards',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$accessToken,
                'Accept: application/com.reloadly.giftcards-v1+json'
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $data = json_decode($response);
        }
        return $data;
    }
    private function getGiftCardsById($product_id = null)
    {
        $data = [];
        if($product_id!=null)
        {
            $accessToken = $this->getAccessToken();
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => GIFT_CARD_API_URL.'/products/'.$product_id,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$accessToken,
                'Accept: application/com.reloadly.giftcards-v1+json'
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
           $data = json_decode($response);
        }
        return $data;
    }
    private function getGiftCardsAccordingToCode($code = null)
    {
        $data = [];
        if($code!=null)
        {
            $accessToken = $this->getAccessToken();
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => GIFT_CARD_API_URL.'/countries/es/products',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$accessToken,
                'Accept: application/com.reloadly.giftcards-v1+json'
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
           $data = json_decode($response);
        }
        return $data;
    }
    private function getAccessToken()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://auth.reloadly.com/oauth/token',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "client_id":"'.general_setting('reloadly_client_id').'",
            "client_secret":"'.general_setting('reloadly_client_secret').'",
            "grant_type":"client_credentials",
            "audience":"'.GIFT_CARD_API_URL.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response);
        $accessToken = isset($result->access_token) ? $result->access_token:'';
        return $accessToken;
    }
}
