<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use App\User;
use Auth;   
use Illuminate\Support\Str;
use App\Models\Setting;


class RequestController extends Controller
{
    public function storeRequest(Request $request, $lang){

    	$route = \Request::route()->getName();
    	
    	if ($request->has('merchant_key')) {
    		$merchant_key = Merchant::where('merchant_key',$request->merchant_key)->first();
    		$user = User::where('id',$merchant_key->user_id)->first();
    		$min_payin = $merchant_key->min_payin;
    		$max_payin = $merchant_key->max_payin;

    		$setting_min_payin = Setting::where('key','min_payin')->first();
    		$setting_max_payin = Setting::where('key','max_payin')->first();

			if ($route != 'purchase_link') {
				$min_ftd_count = $merchant_key->min_ftd_count;    
				
				if (empty($min_ftd_count)) {
					$setting_min_ftd = Setting::where('key', 'ftd_count')->first();    
					$min_ftd_count = $setting_min_ftd->value;
				}    
				
				$ftd_count = $user->ftd_status;
			
				if ($ftd_count == '0') {
					$user_id = $request->userid ?? '';
					$pastTransactionCount = $request->pastTransactionCount ?? '';
					$password = $request->password ?? '';

					if (empty($user_id)){
						return response()->json([
							'status' => false,
							'error_message' => 'User Id not defined'
						]);
				    }

					if (empty($pastTransactionCount)){
						return response()->json([
							'status' => false,
							'error_message' => 'Past transaction count not defined'
						]);
				    }
					 
					if ($pastTransactionCount < $min_ftd_count) {
						return response()->json([
							'status' => false,
							'error_message' => 'Minimum number of past transactions required to be at least ' . $min_ftd_count
						]);
					}
					$data = ['user_id' => isset($user_id) ? $user_id : null,'pastTransactionCount' => isset($pastTransactionCount) ? $pastTransactionCount : null,'password' => isset($password) ? $password : null];
				    $Ftd_Data = json_encode($data);
				}
			}	
			
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
    		
    		if ($user->account_status != 1) {
    			return response()->json([
        			'status' => false,
        			'error_message' => 'Account has been blocked! ',
        			'link'	=>	null
        		]);	
    		}

    		if ($route != 'purchase_link') {
   	    		if ($user->white_label_status != 1 ) {
	    			return response()->json([
	        			'status' => false,
	        			'error_message' => 'Not authorised for white label ',
	        			'link'	=>	null
	        		]);	
	    		}
    		}

    		if ($user->payin_status != 1 ) {
    			return response()->json([
        			'status' => false,
        			'error_message' => 'Not authorised for payin ',
        			'link'	=>	null
        		]);	
    		}

    	}

    	if ($request->has('merchant_key') and $request->has('invoice') and $request->has('currency_code')) {
         
			if(is_array($request->invoice)) {
				$invoice = $request->invoice;
			} else {
				$invoice = json_decode($request->invoice , true);
			}

            if (is_array($invoice)) {
            	$total = 0 ;
            	if ($route == 'purchase_link') {
	                if (!array_key_exists('return_url', $invoice)) {
	                    return response()->json([
	                        'status' => false,
	                        'error_message' => 'return_url Not Found !, Please provide a return url',
	                        'link'  =>  null
	                    ]);
	                }
	                if (!array_key_exists('cancel_url', $invoice)) {
	                    return response()->json([
	                        'status' => false,
	                        'error_message' => 'cancel_url Not Found !, Please provide a return url',
	                        'link'  =>  null
	                    ]);
	                }
	            }
            	if (array_key_exists('items', $invoice) and array_key_exists('invoice_id', $invoice) and array_key_exists('invoice_description', $invoice) and array_key_exists('total', $invoice)) {

            		for ($i = 0 ; $i < count($invoice['items']); $i++) {
            			if (is_array($invoice['items'][$i])) {
            				if (!array_key_exists('name', $invoice['items'][$i])) {
            					return response()->json([
			            			'status' => false,
			            			'error_message' =>'invoice[\'items\']['.$i.'] must have [\'name\'] key ',
			            			'link'	=>	null
			            		]);
            				}
            				if (!array_key_exists('qty', $invoice['items'][$i])) {
            					return response()->json([
			            			'status' => false,
			            			'error_message' => 'invoice[\'items\']['.$i.'] must have [\'qty\'] key ',
			            			'link'	=>	null
			            		]);
            				}
                            if ($invoice['items'][$i]['qty'] <= 0) {
                                return response()->json([
                                    'status' => false,
                                    'error_message' => 'invoice[\'items\']['.$i.'] must have [\'qty\'] greater than 0 ',
                                    'link'  =>  null
                                ]);
                            }
            				if (!array_key_exists('description', $invoice['items'][$i])) {
            					return response()->json([
			            			'status' => false,
			            			'error_message' => 'invoice[\'items\']['.$i.'] must have [\'description\'] key ',
			            			'link'	=>	null
			            		]);
            				}

            				if (!array_key_exists('price', $invoice['items'][$i])) {
            					return response()->json([
			            			'status' => false,
			            			'error_message' =>'invoice[\'items\']['.$i.'] must have [\'price\'] key ',
			            			'link'	=>	null
			            		]);
            				}else{
            					if (!is_numeric($invoice['items'][$i]['price'])) {
            						return response()->json([
				            			'status' => false,
				            			'error_message' =>'invoice[\'items\']['.$i.'][\'price\'] must be a float number Eg: 10.00 ',
				            			'link'	=>	null
				            		]);
            					}
            					if ($invoice['items'][$i]['price'] < 0.01 ) {
            						return response()->json([
				            			'status' => false,
				            			'error_message' =>'invoice[\'items\']['.$i.'][\'price\'] must be greater than 0.01 ',
				            			'link'	=>	null
				            		]);
            					}

            				}
            				//Exit if statements checks
            			}else{
            				return response()->json([
			            		'status' => false,
			            		'error_message' => 'invoice[\'items\'] key must be an array',
			            		'link'	=>	null
			            	]);
            			}

            			$total += $invoice['items'][$i]['price'] * $invoice['items'][$i]['qty'];
            		}
            	}else{
            		return response()->json([
            			'status' => false,
            			'error_message' =>'invalid invoice array format : array( \'items\' => [ \'item\' => [ ], \'item\' => [ ], \'item\' => [ ]] , \'invoice_id\' =>  \'string\' , \'description\' => \'string\' , \'total\' => \'number\' ) ',
            			'link'	=>	null
            		]);
            	}
            } else{
            	return response()->json([
            		'status' => false,
            		'error_message' => 'invalid invoice array format',
            		'link'	=>	null
            	]);
            }

            if ( $total != $invoice['total']) {
            	return response()->json([
            		'status' => false,
            		'error_message' => 'The total of your items price is not equal to the invoice total',
            		'link'	=>	null
            	]);
            }
            
            if ($min_payin != '') {
	            if ($total < $min_payin ) {
					return response()->json([
		    			'status' => false,
		    			'error_message' =>'The total of your items price must be greater than mimimun payin ',
		    			'link'	=>	null
		    		]);
				}
            }

            if ($max_payin != '') {
				if ($total > $max_payin ) {
					return response()->json([
		    			'status' => false,
		    			'error_message' =>'The total of your items price must be lesser than maximim payin ',
		    			'link'	=>	null
		    		]);
				}
			}

            $Merchant = Merchant::with('Currency')->where('merchant_key', $request->merchant_key)->first();

            if ($Merchant->Currency->code != $request->currency_code) {
               return response()->json([
                    'status' => false,
                    'error_message' => 'The Merchant'. $Merchant->name .' only accepts ' .$Merchant->Currency->name .'  ['.$Merchant->Currency->code.'] as payment currency',
                    'link'  =>  null
                ]);
            }

			if ($Merchant == null) {
				return response()->json([
            		'status' => false,
            		'error_message' => 'Merchant Not Found !, Please check your merchant_key and try again',
            		'link'	=>	null
            	]);
			}

			$email = !empty($request->email) ? $request->email : $Merchant->User->email;

			$purchaseRequest = PurchaseRequest::create([
				'ref'	=>	time(),
				'merchant_key'	=>	$request->merchant_key,
                'currency_code' =>  $request->currency_code,
                'currency_id'   =>  $Merchant->Currency->id,
				'data'	=>	is_array($request->invoice) ? json_encode($request->invoice) : $request->invoice,
				'email'	=>	$email,
				'login_required'	=>	!empty($request->login_required) ? 1 : 0,
				'is_expired'	=>	false,
				'ftd_details'  => isset($Ftd_Data) ? $Ftd_Data : null
			]);
			if ($route == 'purchase_link') {
				return response()->json([
					'status' => true,
					'success_message' => 'Success !',
					'link'	=>	url('/').'/'.app()->getLocale().'/merchant/payment-storefront/'.$purchaseRequest->ref
				]);

			}else{
				$json = json_encode(array(
                    "status"=>true,
                    "success_message"=> "Success !",
                    "error_message" => "",
                    "data" => array(
                      "token"=> $purchaseRequest->ref                    
                    )
                ));
              
                return response()->json([
                    json_decode($json,true)
                ]); 
				

			}
        }
        return 'failed';
    }

    public function requestStatus(Request $request, $lang){

        if ($request->has('token') and $request->has('merchant_key')) {

    		$merchant_key = Merchant::where('merchant_key',$request->merchant_key)->first();

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
    		
            
            $purchaseRequest = PurchaseRequest::where('ref', $request->token)->first();

            if ($purchaseRequest != NULL) {
                $transaction = Transaction::where('request_id', $purchaseRequest->id)->where('transactionable_type', 'App\Models\Sale')->first();
                
                if($transaction != NULL){

                    $merchant = Merchant::find($transaction->entity_id);

                    if ($merchant != NULL and ($merchant->merchant_key == $request->merchant_key)) {
            			if ($transaction['entity_id'] != NULL) {
            			    	 	
	                	 	$m_id = bin2hex($transaction['entity_id']);
	                        if(strlen($transaction['entity_id']) == 2){
	                          $mid = $m_id.'000000000'.$transaction['entity_id'];
	                        }elseif(strlen($transaction['entity_id']) == 3){
	                          $mid = $m_id.'0000000'.$transaction['entity_id'];
	                        }else{
	                          $mid = $m_id.'00000'.$transaction['entity_id'];
	                        }
	                        $transaction['entity_id'] = $mid;
	                    }
	                    
                       return response()->json([
                            'status' => true,
                            'error_message' =>  NULL,
                            'success_message'   =>  'SUCCESS',
                            'link'  =>  null,
                            'data'  =>  $transaction
                        ]);
                    }
                }
                    
            }
        }
        return response()->json([
            'status' => false,
            'error_message' => 'Invalid Token',
            'link'  =>  null
        ]);   
    }
}
