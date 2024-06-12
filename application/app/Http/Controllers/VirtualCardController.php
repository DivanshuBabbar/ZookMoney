<?php

namespace App\Http\Controllers;

use Auth;
use \App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\VirtualCard;
use App\Models\VirtualCardDetail;
use App\Models\VirtualCardRequest;

class VirtualCardController extends Controller
{
    
    //protected $secret_key = 'FLWSECK_TEST-a6df2be1e4a442900abc0efba5b94b77-X';
    //protected $secret_key ='FLWSECK_TEST-9eaa1af35fbcb2b5572c27c430265dec-X';
    //protected $secret_key ='FLWSECK_TEST-1c3970ba274308fadf63486eeedaeb01-X';
    protected $secret_key = 'SEC-M8BMAlcByDj9T6sSMlyAS0gkvlLpGEyo';
    // Nohata Code  protected $secret_key = 'FLWSECK-8a60ff71793d4a5d6ed238535f70fce1-X';

    //Response
    // {
    //    "status":"success",
    //    "message":"Card created successfully",
    //    "data":
    //    {
    //       "id":"956962b0-d3a7-4def-8fe4-1bca682cec1e",
    //       "account_id":2044265,
    //       "amount":"200.00",
    //       "currency":"NGN",
    //       "card_pan":"4160461376832806",
    //       "masked_pan":"416046*******2806",
    //       "city":"Lekki",
    //       "state":"Lagos",
    //       "address_1":"19, Olubunmi Rotimi",
    //       "address_2":null,
    //       "zip_code":"23401",
    //       "cvv":"513",
    //       "expiration":"2025-03",
    //       "send_to":null,
    //       "bin_check_name":null,
    //       "card_type":"visa",
    //       "name_on_card":"Flutterwave Developers",
    //       "created_at":"2022-03-08T22:29:52.3802854+00:00",
    //       "is_active":true,
    //       "callback_url":null
    //    }
    // }

    public function list(Request $request, $lang){

      $virtualCards = virtualCard::with('detail')->where('user_id', Auth::user()->id)->orderby('id','desc')->paginate(3);
    	return view('virtualcards.index')
      ->with('virtualcards', $virtualCards);

    }

    public function requestVc(Request $request){

      $this->vCrequestValidation($request);

      $virtualCard = VirtualCard::create([
        'user_id' => Auth::user()->id,
        'virtual_card_id' => uniqid(),
        'currency_id' => 1,
        'virtual_card_issuer' => 'FlutterWave'
      ]);

      $randomnum = random_int(1000, 9999);

      $virtualCardDetail = VirtualCardDetail::create([
        'user_id' => Auth::user()->id,
        'virtualcard_id' =>  $virtualCard->id,
        'card_id'  =>  uniqid(),
        'hash'  =>  uniqid(),
        'pan' =>  random_int(1000, 9999).' '.random_int(1000, 9999).' '.random_int(1000, 9999).' '.$randomnum,
        'masked_pan'  =>  '**** **** **** ' . $randomnum,
        'name_on_card' => Auth::user()->first_name . ' ' .  Auth::user()->last_name,
        'zip_code'  =>  '0000',
        'cvv' =>  random_int(100, 999),
        'amount' => $request->amount,
        'expiration'  => '03/23',
        'card_created_at' => \Carbon\Carbon::now(),
        'message' =>  'Card waiting to be processed ... '
      ]);

      $virtualCardRequest = VirtualCardRequest::create([
        'user_id' => Auth::user()->id,
        'virtualcard_id' =>  $virtualCard->id,
        'virtualcarddetail_id' => $virtualCardDetail->id,
        'currency_id' => 1,
        'virtual_card_issuer' => 'FlutterWave',
        'request_resolution' => 'Waiting to be processed',
      ]);

      flash(__(" Your Virtualcard request was successfull, you will receive the card details via email when it is created ! "),'success');
          return back();

    }

    public function createVirtualCard(Request $request){

        $this->vCrequestValidation($reuest);

        ///CURLOPT_URL => "https://api.flutterwave.com/v3/virtual-cards",
       
        $data =array(
          "currency" =>  "NGN",
          "amount" =>  200,
          "debit_currency" =>  "NGN",
          // "first_name" => "Sonia", Paychangu
          // "last_name" => "Jackson", Paychangu
          //"billing_name" =>  "Hose Santos Developers",  Flutterwave
          // "billing_address" =>  "333 Fremont Street",
          // "billing_city" =>  "San Francisco",
          // "billing_state" =>  "CA",
          // "billing_postal_code" =>  "94105",
          // "billing_country" =>  "US"
          "callback_url" => url('/').'/home'

        );


        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://in.paychangu.com/api/virtual_card/create",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($data),
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$this->secret_key,
            "Content-Type: application/json"
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        dd($response);
        

         
    }

    private function vCrequestValidation(Request $request){

       $wallet = Wallet::where('user_id', Auth::user()->id)->where('currency_id', 1)->first();

        if($wallet == null){
            flash(__('Something went wrong !'), 'danger');
            return back();
        }


        if(!isset($request->amount)){
            flash(__('The amount fied is required'), 'danger');
            return back();
        }

        if($request->amount < setting('cards.vt_min') and $request->amount > setting('cards.vt_max')){

            flash(__(" The card amount must be between USD setting('cards.vt_min') and $request->amount > setting('cards.vt_max')"),'danger');
            return back();
        }

        if($wallet->amount < $request->amount){

            flash(__(" insuficient funds to create a USD $request->amount Virtual Card."),'danger');
            return back();

        }


        $this->validate($request,[
            'amount' =>  'numeric',
        ]);

    }

}
