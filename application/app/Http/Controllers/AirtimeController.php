<?php
namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Receive;
use App\Models\Transactions;
use App\Models\Currrency;
use App\Helpers\Money;
class AirtimeController extends Controller
{
  public function airtime()
  {
    // echo 'here';die();
    $currency_detail = DB::table('currencies')->where(['code'=>'USD'])->first();
    $currency_id = isset($currency_detail->id) ? $currency_detail->id:'';
    $data['currency'] = '';
    if($currency_id)
    {
      $data['currency'] = $currency = Auth::user()->walletByCurrencyId($currency_id);
    }
    $data['countries']=$this->getAirtimeCountries();
    $data['page_title'] = "Buy Airtime";
    return view('airtime.buy_airtime', $data);
  }
  public function getOperatores($lange = null,$country_code = null,$currency_code = null)
  {
      if($country_code!=null)
      {
      	$data['operators']=$this->getCountriesOperators($country_code);
      	$data['country_code']=$country_code;
      	$data['currency_code']=$currency_code;
      }
      $data['page_title'] = "Buy Airtime";
      $data['countries']=$this->getAirtimeCountries();
      return view('airtime.buy_airtime', $data);
  }
  public function buyAirtimeRequest(Request $request)
  {
  	$amount = $request->amount;
  	$currency_data = $this->getCurrency();
  	$currency_id = isset($currency_data->id) ? $currency_data->id:'';
  	if (!$currency_id) 
  	{
      flash(__('Your Currency Not Found!!'),'danger');
      return back();
    }
  	$userWallet = Auth::user()->walletByCurrencyId($currency_id);
    if (!$userWallet) 
    {
      flash(__('Your Wallet Not Found!!'),'danger');
      return back();
    }
    if($userWallet->fiat < $amount)
    {
      flash(__('Insufficient  Balance !!'),'danger');
      return back();
    }
    $total_deduction = $amount;
    $post_field = [];
    $post_field['operatorId'] = $request->operator_id;
    $post_field['amount'] = $amount;
    $post_field['useLocalAmount'] = false;
    $post_field['customIdentifier'] = rand();
    $receipient_array['countryCode'] = $request->country;
    $receipient_array['number'] = $request->phone_number;
    $post_field['recipientPhone'] = $receipient_array;
    $post_field = json_encode($post_field);
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://topups.reloadly.com/topups',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$post_field,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$this->getAirtimeAccessToken().'',
        'Accept: application/com.reloadly.topups-v1+json',
        'Content-Type: application/json'
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($response);
    $transactionId = isset($response->transactionId) ? $response->transactionId:'';
    if($transactionId)
    {
    	//trx  for user
        $remaining_fiat = ($userWallet->fiat - $total_deduction);
        DB::table('wallets')->where(['currency_id'=>$currency_id,'user_id'=>auth()->user()->id])->update(['fiat'=>$remaining_fiat]);
        $this->postTransactionable($total_deduction,$transactionId);
        flash(__('Successfully Completed.'),'success');
        return back();
    }
    else
    {
        $message = isset($response->message) ? $response->message:'';
        flash(__($message),'danger');
        return back();
    }
    return $response;
  }
  private function getCurrency($currency_code=null)
  {
    $data = DB::table('currencies')->where('code','USD')->first();
    return $data;
  }
  private function postTransactionable($amount = 0,$transactionable_id=null,$fee=0)
  {
      $currency = $this->getCurrency();
      $currency_name = isset($currency->name) ? $currency->name:'';
      $currency_code = isset($currency->code) ? $currency->code:'';
      $currency_id = isset($currency->id) ? $currency->id:'';
      $currency_symbol = isset($currency->symbol) ? $currency->symbol:'';
      $wallet = Auth::user()->walletByCurrencyId($currency_id);
      $balance = isset($wallet->fiat) ? $wallet->fiat:0;
      $transactions = [];
      $transactions['user_id'] = auth()->user()->id;
      $transactions['gross'] = $amount;
      $transactions['fee'] = $fee;
      $transactions['net'] = ($amount + $fee);
      $transactions['balance'] = $balance;
      $transactions['transactionable_id'] = $transactionable_id;
      $transactions['transactionable_type'] = 'Amount Recharged';
      $transactions['entity_name'] = $currency_name;
      $transactions['entity_id'] = $transactionable_id;
      $transactions['currency'] = $currency_code;
      $transactions['transaction_state_id'] = 1;
      $transactions['currency_symbol'] = $currency_symbol;
      $transactions['currency_id'] = $currency_id;
      $transactions['activity_title'] = 'Amount Recharged';
      $transactions['money_flow'] = '-';
      $transactions['created_at'] = date('Y-m-d H:i:s');
      $transactions['updated_at'] = date('Y-m-d H:i:s');
      DB::table('transactionable')->insert($transactions);
  }
  private function getCurrencyIdAccordingToCountry($code = null)
  {
  	$currency_data = '';
  	if($code!=null)
  	{
  		$currency_data = Currency::where(['code'=>$code])->first();
  	}
  	return $currency_data;
  }
  public function getAirtimeAccessToken()
  {
      $_response = $this->fetchAccessTokenFromAritime();
      $access_token = isset($_response->access_token) ? $_response->access_token:'';
      // $access_token = 'eyJraWQiOiI1N2JjZjNhNy01YmYwLTQ1M2QtODQ0Mi03ODhlMTA4OWI3MDIiLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI5NDIiLCJpc3MiOiJodHRwczovL3JlbG9hZGx5LXNhbmRib3guYXV0aDAuY29tLyIsImh0dHBzOi8vcmVsb2FkbHkuY29tL3NhbmRib3giOnR';
      return $access_token;
  }
  public function fetchAccessTokenFromAritime()
  {
      $post_field = array(
         'client_id'=>general_setting('reloadly_client_id'),
         'client_secret'=>general_setting('reloadly_client_secret'),
          'audience'=>'https://topups.reloadly.com',
          'grant_type'=>'client_credentials'
      );
      $post_field = json_encode($post_field);
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
        CURLOPT_POSTFIELDS =>$post_field,
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
        ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);
      $response = json_decode($response);
      return $response;
  }
  public function getAirtimeCountries()
  {
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://topups.reloadly.com/countries?page=1&size=1',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Accept: application/com.reloadly.topups-v1+json',
          'Authorization: Bearer '.$this->getAirtimeAccessToken().''
        ),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      $response = json_decode($response);
      return $response;
  }
  public function getCountriesOperators($country_code=null)
  {
      $_response = $this->fetchAccessTokenFromAritime();
      $access_token = isset($_response->access_token) ? $_response->access_token:'';
      $response = '';
      if($country_code!=null)
      {
          $curl = curl_init();

          curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://topups.reloadly.com/operators/countries/'.$country_code.'?includeBundles=true&includeData=true&includePin=true&suggestedAmounts=true&suggestedAmountsMap=true',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
              'Authorization: Bearer '.$access_token.'',
              'Accept: application/com.reloadly.topups-v1+json',
              'Content-Type: application/json'
            ),
          ));
          $response = curl_exec($curl);
          curl_close($curl);
          $response = json_decode($response);
      }
      return $response;
  }
}