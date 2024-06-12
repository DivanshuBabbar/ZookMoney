<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use App\Models\Currency;
use App\Models\Transaction;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use App\Models\Merchant;
use App\Models\Deposit;
use App\Models\DepositMethod;
use App\Models\TransferMethod;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Money;
use App\Models\Setting;


class WhitelabelController extends Controller
{
    public function getDepositDetails(Request $request, $lang)
    {
      
        if (empty($request->merchant_key)) {
          
            return response()->json([
                'status' => false,
                'error_message' => 'No merchant key provided',
            ]);
        }

        if (empty($request->token)) {
           
            return response()->json([
                'status' => false,
                'error_message' => 'No token provided',
            ]);
        }

        if (empty($request->type)) {
           
            return response()->json([
                'status' => false,
                'error_message' => 'No type provided',
            ]);
        }

        if ($request->merchant_key) {
            $merchant = Merchant::where('merchant_key',$request->merchant_key)->first();
            if (empty($merchant)) {
                return response()->json([
                    'status' => false,
                    'error_message' => 'No merchant found!',
                ]);
            }
        }

        if ($request->has('merchant_key')) {
      
            $merchant_key = Merchant::where('merchant_key',$request->merchant_key)->first();
            $user = User::where('id',$merchant_key->user_id)->first();
            $min_payin = $merchant_key->min_payin;
            $max_payin = $merchant_key->max_payin;

            $setting_min_payin = Setting::where('key','min_payin')->first();
            $setting_max_payin = Setting::where('key','max_payin')->first();

            if ($min_payin == '') {
                $min_payin = $setting_min_payin->value;
            }
            if ($max_payin == '') {
                $max_payin = $setting_max_payin->value;
            }

            if ($merchant_key->status == 'Approved') {
                $site_url = $merchant_key->site_url;
                $host = \Request::root();

                if ($site_url != $host) {
                    return response()->json([
                        'status' => false,
                        'error_message' => 'Requested url does not match with the merchant site url!'
                    ]);
                }
            }

            if ($merchant_key->status == 'Rejected') {
                return response()->json([
                    'status' => false,
                    'error_message' => 'Merchant Rejected!'
                ]);
            }
            
            
            if ($user->white_label_status != 1) {
                return response()->json([
                    'status' => false,
                    'error_message' => 'User Request For White Label Has Been Blocked',
                    'link'  =>  null
                ]); 
            }

            if ($user->account_status != 1) {
                return response()->json([
                    'status' => false,
                    'error_message' => 'User Account has been blocked!',
                    'link'  =>  null
                ]); 
            }

            if ($user->wire_transfer_status != 1) {
                return response()->json([
                    'status' => false,
                    'error_message' => 'User Request For Wire Transfer Has Been Blocked',
                    'link'  =>  null
                ]); 
            }
        }
        

        if ($request->merchant_key && $request->token && $request->type == 'wire') {
            return response()->json([
                'status' => false,
                'error_message' => 'Not authorised for wire transfer',
            ]);
        }

        if ($request->merchant_key && $request->token && $request->type == 'upi') {
           
            $purchaseRequest = PurchaseRequest::with('Transaction')->with('Currency')->where('ref', $request->token)->first();

            if ($purchaseRequest != NULL) {

                $email = $purchaseRequest->email;
                $user = User::where('email', $email)->first();
                $currency = Currency::where('code', $purchaseRequest->currency_code)->first();
                $transferMethod = TransferMethod::where('currency_id', $currency->id)->where('is_system', 1)->first();

                $total = 0;
                foreach ($purchaseRequest->data->items as $item) {
                    $total += ( $item->price * $item->qty );
                }
                
                if ($min_payin != '') {
                    if ($total < $min_payin ) {
                        return response()->json([
                            'status' => false,
                            'error_message' =>'The total of your items price must be greater than mimimun payin ',
                            
                        ]);
                    }
                }

                if ($max_payin != '') {
                    if ($total > $max_payin ) {
                        return response()->json([
                            'status' => false,
                            'error_message' =>'The total of your items price must be lesser than maximim payin ',
                            
                        ]);
                    }
                }


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
                            'currency_id'   =>   $currency->id,
                            'is_merchant'   =>  0,
                            'verified'  => 1,
                            'verification_token'  => NULL,
                        ]);

                        $wallet = wallet::create([
                            'is_crypto' =>  $currency->is_crypto,
                            'user_id'   => $user->id,
                            'amount'    =>  0,
                            'currency_id'   => $currency->id,
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
                                'user_id'   => $user->id,
                                'amount'    =>  0,
                                'currency_id'   => $currency->id,
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
                        'merchantId' => $request->merchant_key,
                        'token' => $request->token

                    ];
             
                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL,            $url );
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
                    curl_setopt($ch, CURLOPT_POST,           true );
                    curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode($data) ); 
                    curl_setopt($ch, CURLOPT_HTTPHEADER,     $headers); 

                    $result = json_decode(curl_exec($ch), true);

                    $qrServicePayload = array_except($result, ['gqrs']);
            
                    $deposit->qr_service_payload = json_encode($qrServicePayload);
                    $deposit->save();


                    $qr = $result['qr'];
                    $showAmount = $result['showAmount'];

                    $json = json_encode(array(
                        "status"=>true,
                        "success_message"=> "Success !",
                        "error_message" => "",
                        "data" => array(
                          "qr"=> $qr,
                          "link"=>null,
                          "amount"=>$showAmount
                         
                        )
                    ));
                  
                    return response()->json([
                        json_decode($json,true)
                    ]); 
               

                } catch (\Exception $ex) {
                    DB::rollBack();
                    flash(__('Wops, something went wrong... please contact support if this error persists !'), 'danger');
                    abort(400);
                }

                
            }
            return response()->json([
                'status' => false,
                'error_message' => 'Invalid Token'
            ]); 

        }


    }
}