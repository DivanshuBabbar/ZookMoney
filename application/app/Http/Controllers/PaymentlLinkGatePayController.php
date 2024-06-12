<?php
namespace App\Http\Controllers;
use App\User;
use \App\Models\PaymentLink;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\AirtimeTransaction;
use App\Models\Transaction;
use App\Models\DepositHistory;
use Session;
use Image;
use File;
use Validator;
use GUMP;
use App\Models\Currency;
use App\Models\Wallet;
use App\Helpers\Money;
require(base_path().'/resources/views/gatepay/vendor/autoload.php');
if(!defined('PAY_PAGE_CONFIG')) 
{
    define('PAY_PAGE_CONFIG', base_path().'/resources/views/gatepay/config.php');
}
use App\Components\Payment\PaymentProcess;
use App\Service\PaytmService;
use App\Service\InstamojoService;
use App\Service\IyzicoService;
use App\Service\PaypalService;
use App\Service\PaystackService;
use App\Service\RazorpayService;
use App\Service\StripeService;
use App\Service\AuthorizeNetService;
use App\Service\BitPayService;
use App\Service\MercadopagoService;
use App\Service\PayUmoneyService;
use App\Service\MollieService;
use App\Service\RavepayService;
use App\Service\PagseguroService;
use App\Service\StrowalletService;

use App\Components\Payment\PaytmResponse;
use App\Components\Payment\PaystackResponse;
use App\Components\Payment\StripeResponse;
use App\Components\Payment\RazorpayResponse;
use App\Components\Payment\InstamojoResponse;
use App\Components\Payment\IyzicoResponse;
use App\Components\Payment\PaypalIpnResponse;
use App\Components\Payment\BitPayResponse;
use App\Components\Payment\MercadopagoResponse;
use App\Components\Payment\PayUmoneyResponse;
use App\Components\Payment\MollieResponse;
use App\Components\Payment\RavepayResponse;
use App\Components\Payment\PagseguroResponse;
class PaymentlLinkGatePayController extends Controller
{
    public function __construct()
    {
        $this->fixed_fee = 0;
        $this->percentage_fee = 0;
    }
    public function payment_gatepay_cancel()
    {
        $redirect_url = route('home',app()->getLocale());
        if(session()->has('redirect_url'))
        {
            $redirect_url = session()->get('redirect_url');
        }
        flash('Falied' ,'danger');
        return redirect($redirect_url);
    }
    public function paymentlinkGateWay($lang, $payment_id)
    {
      $paymentlink = PaymentLink::where('paymentlink_id', $payment_id)->first();
      $user = User::find($paymentlink->user_id);
      session()->put('link_owner_id',$user->id);
      $paymentlink ?? abort(404);
      $data['paymentlink']=$paymentlink;
      $data['user']=$user;
      $data['item_name'] = 'wallet funding';
      $callback_url = '/payment_gatepay_callback';
      session()->put('gateway_callback_url',$callback_url);
      $redirect_url = route('paymentLinkStoreFront',[app()->getLocale(),$payment_id]);
      session()->put('redirect_url',$redirect_url);
      return view('gatepay.payment_link_gateway',$data);
    }
    public function payment_gatepay_callback()
    {
        $redirect_url = route('home',app()->getLocale());
        if(session()->has('redirect_url'))
        {
            $redirect_url = session()->get('redirect_url');
        }
        $requestData = $_REQUEST;
        // echo '<pre>',print_r($requestData),'</pre>';exit();
        // Get Config Data
        $configData = configItem();
        if($requestData['paymentOption'] == 'strowallet') 
        {
            $paymentResponseData = [
                'status'   => true,
                'rawData'  => $requestData,
                'data'     => $this->preparePaymentData($requestData['custom'], $requestData['amount'],$requestData['custom'], $requestData['currency'], 'strowallet')
            ];
            $this->paymentResponse($paymentResponseData);
            flash('Payment Done successfully' ,'success');
            return redirect($redirect_url);
        } 
        // Check payment Method is paytm
        if ($requestData['paymentOption'] == 'paytm') {
            // Get Payment Response instance
            $paytmResponse  = new PaytmResponse();
            // Fetch payment data using payment response instance
            $paytmData = $paytmResponse->getPaytmPaymentData($requestData);

            // Check if payment status is success
            if ($paytmData['STATUS'] == 'TXN_SUCCESS') {
                // Create payment success response data.
                $paymentResponseData = [
                    'status'   => true,
                    'rawData'  => $paytmData,
                    'data'     => $this->preparePaymentData($paytmData['ORDERID'], $paytmData['TXNAMOUNT'], $paytmData['TXNID'], 'paytm')
                ];
                // Send data to payment response.
                $this->paymentResponse($paymentResponseData);
            } else {
                // Create payment failed response data.
                $paymentResponseData = [
                    'status'   => false,
                    'rawData'  => $paytmData,
                    'data'     => $this->preparePaymentData($paytmData['ORDERID'], $paytmData['TXNAMOUNT'], $paytmData['TXNID'], 'paytm')
                ];
                // Send data to payment response function
                $this->paymentResponse($paymentResponseData);
            }
        // Check payment method is instamojo
        } elseif ($requestData['paymentOption'] == 'instamojo') {
            $instamojoResponse  = new InstamojoResponse();
            $instamojoData = $instamojoResponse->getInstamojoPaymentData($requestData);
            if ($requestData['payment_status'] == "Credit") 
            {
                $paymentResponseData = [
                    'status'   => true,
                    'rawData'  => $instamojoData,
                    'data'     => $this->preparePaymentData($requestData['orderId'], $instamojoData['amount'], $instamojoData['payment_id'],'INR','instamojo')
                ];
                $this->paymentResponse($paymentResponseData);
                flash('Payment Done successfully' ,'success');
                return redirect($redirect_url);
            } 
            else 
            {
                $paymentResponseData = [
                    'status'   => false,
                    'rawData'  => $requestData,
                    'data'     => $this->preparePaymentData($requestData['orderId'], null,null,null, 'instamojo')
                ];
                $this->paymentResponse($paymentResponseData);
                flash('Falied' ,'danger');
                return redirect($redirect_url);
            }

        // Check if payment method is iyzico.
        } elseif ($requestData['paymentOption'] == 'iyzico') {
            // Check if payment status is success for iyzico.
            if ($_REQUEST['status'] == 'success') {
                // Get iyzico response.
                $iyzicoResponse  = new IyzicoResponse();

                // fetch payment data using iyzico response instance.
                $iyzicoData = $iyzicoResponse->getIyzicoPaymentData($requestData);
                $rawResult = json_decode($iyzicoData->getRawResult(), true);

                // Check if iyzico payment data is success
                // Then create a array for success data
                if ($iyzicoData->getStatus() == 'success') {
                    $paymentResponseData = [
                        'status'   => true,
                        'rawData'  => (array) $iyzicoData,
                        'data'     => $this->preparePaymentData($requestData['orderId'], $rawResult['price'], $rawResult['conversationId'], 'iyzico')
                    ];
                    // Send data to payment response
                    $this->paymentResponse($paymentResponseData);
                // If payment failed then create data for failed
                } else {
                    // Prepare failed payment data
                    $paymentResponseData = [
                        'status'   => false,
                        'rawData'  => (array) $iyzicoData,
                        'data'     => $this->preparePaymentData($requestData['orderId'], $rawResult['price'], $rawResult['conversationId'], 'iyzico')
                    ];
                    // Send data to payment response
                    $this->paymentResponse($paymentResponseData);
                }
            // Check before 3d payment process payment failed
            } else {
                // Prepare failed payment data
                $paymentResponseData = [
                    'status'   => false,
                    'rawData'  => $requestData,
                    'data'     => $this->preparePaymentData($requestData['orderId'], $rawResult['price'], null, 'iyzico')
                ];
                // Send data to process response
                $this->paymentResponse($paymentResponseData);
            }

        // Check Paypal payment process
        } 
        else if ($requestData['paymentOption'] == 'paypal') 
        {
            $paypalIpnResponse  = new PaypalIpnResponse();
            // $paypalIpnData = $paypalIpnResponse->getPaypalPaymentData();
            $paypalIpnData = json_encode($requestData);
            // $rawData = json_decode($paypalIpnData, true);
            // echo '<pre>',print_r($requestData),'</pre>';exit();
            if (isset($requestData['payment_status']) and $requestData['payment_status'] == "Completed") 
            {
                $paymentResponseData = [
                    'status'    => true,
                    'rawData'   => (array) $paypalIpnData,
                    'data'     => $this->preparePaymentData($requestData['invoice'], $requestData['payment_gross'], $requestData['txn_id'],$requestData['mc_currency'], 'paypal')
                    // 'data'     => $this->preparePaymentData($rawData['invoice'], $rawData['payment_gross'], $rawData['txn_id'], 'paypal')
                ];
                $this->paymentResponse($paymentResponseData);
                // $this->paymentResponse($paymentResponseData);
                flash('Payment Done successfully' ,'success');
                return redirect($redirect_url);
            } 
            else 
            {
                $paymentResponseData = [
                    'status'   => false,
                    'rawData'  => [],
                    'data'     => $this->preparePaymentData(null, null, null,null, 'paypal')
                ];
                $this->paymentResponse($paymentResponseData);
                flash('Failed' ,'danger');
                return redirect($redirect_url);
            }
        } 
        else if ($requestData['paymentOption'] == 'paystack') 
        {
            $requestData = json_decode($requestData['response'], true);
            if (isset($requestData['status']) and $requestData['status'] == "success") 
            {
                $paymentResponseData = [
                    'status'   => true,
                    'rawData'   => $requestData,
                    'data'     => $this->preparePaymentData($requestData['data']['reference'], $requestData['data']['amount']/100,$requestData['data']['reference'],$requestData['data']['currency'],'paystack')
                ];
                $this->paymentResponse($paymentResponseData);
                flash('Payment Done successfully' ,'success');
                return redirect($redirect_url);
            } 
            else 
            {
                $paymentResponseData = [
                    'status'   => false,
                    'rawData'   => $requestData,
                    'data'     => $this->preparePaymentData($requestData['data']['reference'], $requestData['data']['amount'],null,$requestData['data']['reference'], 'paystack')
                ];
                $this->paymentResponse($paymentResponseData);
                flash('Falied' ,'danger');
                return redirect($redirect_url);
            }
        // Check Stripe payment process
        } elseif ($requestData['paymentOption'] == 'stripe') {
            $stripeResponse = new StripeResponse();

            $stripeData = $stripeResponse->retrieveStripePaymentData($requestData['stripe_session_id']);
            // Check if payment charge status key exist in stripe data and it success
            if (isset($stripeData['status']) and $stripeData['status'] == "succeeded") {
                // echo '<pre>',print_r($stripeData),'</pre>';exit()
                // Prepare data for success
                $paymentResponseData = [
                    'status'   => true,
                    'rawData'   => $stripeData,
                    'data'     => $this->preparePaymentData($requestData['orderId'], $stripeData->amount/100, $stripeData->charges->data[0]['balance_transaction'],$stripeData->charges->data[0]['currency'], 'stripe')
                ];
                $this->paymentResponse($paymentResponseData);
                flash('Payment Done successfully' ,'success');
                return redirect($redirect_url);
            // Check if stripe data is failed
            } else {
                // Prepare failed payment data
                $paymentResponseData = [
                    'status'   => false,
                    'rawData'   => $stripeData,
                    'data'     => $this->preparePaymentData($requestData['orderId'], $stripeData->amount, null,null,'stripe')
                ];
                $this->paymentResponse($paymentResponseData);
                flash('Falied' ,'danger');
                return redirect($redirect_url);
            }
        // Check Razorpay payment process
        } elseif ($requestData['paymentOption'] == 'razorpay') {
            // $orderId = $requestData['orderId'];
            $requestData = json_decode($requestData['response'], true);
            if (isset($requestData['status']) and $requestData['status'] == 'captured') 
            {
                $paymentResponseData = [
                    'status'   => true,
                    'rawData'   => $requestData,
                    'data'     => $this->preparePaymentData($requestData['id'], $requestData['amount']/100, $requestData['id'],$requestData['currency'], 'razorpay')
                ];
                $this->paymentResponse($paymentResponseData);
                flash('Payment Done successfully' ,'success');
                return redirect($redirect_url);
            } 
            else 
            {
                $paymentResponseData = [
                    'status'   => false,
                    'rawData'   => $requestData,
                    'data'     => $this->preparePaymentData($orderId, $requestData['amount'], $requestData['id'],null,null, 'razorpay')
                ];
                $this->paymentResponse($paymentResponseData);
                flash('Falied' ,'danger');
                return redirect($redirect_url);
            }
        } elseif ($requestData['paymentOption'] == 'authorize-net') {
            $orderId = $requestData['order_id'];

            $requestData = json_decode($requestData['response'], true);

            // Check if razorpay status exist and status is success
            if (isset($requestData['status']) and $requestData['status'] == 'success') {
                // prepare payment data
                $paymentResponseData = [
                    'status'   => true,
                    'rawData'   => $requestData,
                    'data'     => $this->preparePaymentData($orderId, $requestData['amount'], $requestData['transaction_id'], 'authorize-net')
                ];
                // send data to payment response
                $this->paymentResponse($paymentResponseData);
            // razorpay status is failed
            } else {
                // prepare payment data for failed payment
                $paymentResponseData = [
                    'status'   => false,
                    'rawData'   => $requestData,
                    'data'     => $this->preparePaymentData($orderId, $requestData['amount'], $requestData['transaction_id'], 'authorize-net')
                ];
                // send data to payment response
                $this->paymentResponse($paymentResponseData);
            }
        } elseif ($requestData['paymentOption'] == 'bitpay') {
            // prepare payment data
            $paymentResponseData = [
                'status'   => true,
                'rawData'  => $requestData,
                'data'     => $this->preparePaymentData($requestData['orderId'], $requestData['amount'], $requestData['orderId'],'USD','bitpay')
            ];
            // send data to payment response
            $this->paymentResponse($paymentResponseData);
        } elseif ($requestData['paymentOption'] == 'bitpay-ipn') {
            $bitpayResponse = new BitPayResponse();
            $rawPostData = file_get_contents('php://input');
            $ipnData = $bitpayResponse->getBitPayPaymentData($rawPostData);
            if($ipnData['status'] == 'success') 
            {
                flash('Payment Done successfully' ,'success');
                return redirect($redirect_url);
            } 
            else 
            {
                flash('Falied' ,'danger');
                return redirect($redirect_url);
            }
        } elseif ($requestData['paymentOption'] == 'mercadopago') {
            if ($requestData['collection_status'] == 'approved') {
                $paymentResponseData = [
                    'status'   => true,
                    'rawData'   => $requestData,
                    'data'     => $this->preparePaymentData($requestData['order_id'], $requestData['amount'], $requestData['collection_id'], 'mercadopago')
                ];
            } elseif ($requestData['collection_status'] == 'pending') {
                $paymentResponseData = [
                    'status'   => 'pending',
                    'rawData'   => $requestData,
                    'data'     => $this->preparePaymentData($requestData['order_id'], $requestData['amount'], $requestData['collection_id'], 'mercadopago')
                ];
            } else {
                $paymentResponseData = [
                    'status'   => false,
                    'rawData'   => $requestData,
                    'data'     => $this->preparePaymentData($requestData['order_id'], $requestData['amount'], $requestData['collection_id'], 'mercadopago')
                ];
            }
            $this->paymentResponse($paymentResponseData);
        } elseif ($requestData['paymentOption'] == 'mercadopago-ipn') {
            $mercadopagoResponse = new MercadopagoResponse();
            $mercadopagoIpnData = $mercadopagoResponse->getMercadopagoPaymentData($requestData);

        // Ipn data recieved here are as following
        //$mercadopagoIpnData['status'] = 'total_paid or not_paid';
        //$mercadopagoIpnData['message'] = 'Message';
        //$mercadopagoIpnData['raw_data'] = 'Raw Ipn Data';
        } elseif ($requestData['paymentOption'] == 'payumoney') {
            $payUmoneyResponse = new PayUmoneyResponse();
            $payUmoneyResponseData = $payUmoneyResponse->getPayUmoneyPaymentResponse($requestData);
            if ($payUmoneyResponseData['status'] == 'success') {
                $paymentResponseData = [
                    'status'    => true,
                    'order_id'  => $payUmoneyResponseData['raw_Data'],
                    'rawData'   => $payUmoneyResponseData['raw_Data'],
                    'data'      => $this->preparePaymentData($payUmoneyResponseData['order_id'], $payUmoneyResponseData['amount'], $payUmoneyResponseData['txn_id'], 'payumoney')
                ];
            } elseif ($payUmoneyResponseData['status'] == 'failed') {
                $paymentResponseData = [
                    'status'    => false,
                    'order_id'  => '',
                    'rawData'   => $payUmoneyResponseData['raw_Data'],
                    'data'      => $this->preparePaymentData($payUmoneyResponseData['order_id'], $payUmoneyResponseData['amount'], $payUmoneyResponseData['txn_id'], 'payumoney')
                ];
            }
            $this->paymentResponse($paymentResponseData);
        } elseif ($requestData['paymentOption'] == 'mollie') {
            $paymentResponseData = [
                'status'    => true,
                'order_id'  => $requestData['order_id'],
                'rawData'   => $requestData,
                'data'      => $this->preparePaymentData($requestData['order_id'], $requestData['amount'],$requestData['order_id'],'EUR', 'mollie')
            ];
            $this->paymentResponse($paymentResponseData);
            return redirect()->route('auto_deposit_success', app()->getLocale());
        } elseif ($requestData['paymentOption'] == 'mollie-webhook') {
            $mollieResponse = new MollieResponse();
            $webhookData = $mollieResponse->retrieveMollieWebhookData($requestData);

        // mollie webhook data received here with following option
        // $webhookData['status']; - payment status (paid|open|pending|failed|expired|canceled|refund|chargeback)
        // $webhookData['raw_data']; - webhook all raw data
        // $webhookData['message']; - if payment failed then message

        // Check Ravepay payment process
        } 
        else if ($requestData['paymentOption'] == 'ravepay') 
        {
            $requestData = json_decode($requestData['response'], true);
            if (isset($requestData['body']['status']) and $requestData['body']['status'] == "success") 
            {
                $paymentResponseData = [
                    'status'   => true,
                    'rawData'   => $requestData,
                    'data'     => $this->preparePaymentData($requestData['body']['data']['txref'], $requestData['body']['data']['amount'], $requestData['body']['data']['txid'],$requestData['body']['data']['currency'], 'ravepay')
                ];
                $this->paymentResponse($paymentResponseData);
                flash('Payment Done successfully' ,'success');
                return redirect($redirect_url);
            } 
            else 
            {
                $paymentResponseData = [
                    'status'   => false,
                    'rawData'   => $requestData,
                    'data'     => $this->preparePaymentData($requestData['body']['data']['txref'], $requestData['body']['data']['amount'], $requestData['body']['data']['txid'], 'ravepay')
                ];
                $this->paymentResponse($paymentResponseData);
                flash('Falied' ,'danger');
                return redirect($redirect_url);
            }
        // Check Pagseguro payment process
        } elseif ($requestData['paymentOption'] == 'pagseguro') {
            // Get Payment Response instance
            $pagseguroResponse  = new PagseguroResponse();

            // Fetch payment data using payment response instance
            $pagseguroData = $pagseguroResponse->fetchTransactionByRefrenceId($requestData['reference_id']);

            //handling errors
            if (isset($pagseguroData['status']) and $pagseguroData['status'] == 'error') {
                //throw exception when generate errors
                throw new Exception($pagseguroData['message']);
            }

            //transaction status
            //1 - Awaiting payment, 2 - In analysis, 3 - Pay, 4 - Available, 5 - In dispute,
            //6 - Returned, 7 - Canceled
            $txnStatus = $pagseguroData['responseData']->getTransactions()[0]->getStatus();

            //collect transaction code
            $transactionCode = $pagseguroData['responseData']->getTransactions()[0]->getCode();

            // Fetch transaction data by transaction code
            $transactionData = $pagseguroResponse->fetchTransactionByTxnCode($transactionCode);

            // Check if payment status is success
            if ($transactionData['status'] == 'success' and $txnStatus == 3 and $transactionData['responseData']->getReference() == $requestData['reference_id']) {
                // Create payment success response data.
                $paymentResponseData = [
                    'status'   => true,
                    'rawData'  => $transactionData['responseData'],
                    'data'     => $this->preparePaymentData(
                        $transactionData['responseData']->getReference(),
                        $transactionData['responseData']->getGrossAmount(),
                        $transactionData['responseData']->getCode(),
                        'pagseguro'
                    )
                ];
                // Send data to payment response.
                $this->paymentResponse($paymentResponseData);
            } else {
                // Create payment failed response data.
                $paymentResponseData = [
                    'status'   => false,
                    'rawData'  => $paytmData,
                    'data'     => $this->preparePaymentData(
                        $transactionData['responseData']->getReference(),
                        $transactionData['responseData']->getGrossAmount(),
                        $transactionData['responseData']->getCode(),
                        'pagseguro'
                    )
                ];
                // Send data to payment response function
                $this->paymentResponse($paymentResponseData);
            }
        }

        // $data['requestData'] = $requestData;
        // return view('gatepay.payment-response', $data);
    }
    private function paymentResponse($paymentResponseData)
    {
        if($paymentResponseData['status'] === true) 
        {
            $this->postData($paymentResponseData);
        }
    }
    private function preparePaymentData($orderId, $amount, $txnId,$currency, $paymentGateway)
    {
        return [
            'order_id'              => $orderId,
            'amount'                => $amount,
            'payment_reference_id'  => $txnId,
            'currency'  => $currency,
            'payment_gateway'        => $paymentGateway
        ];
    }
    private function postData($paymentResponseData)
    {
        $user_id = session()->get('link_owner_id');
        $user = User::findOrFail($user_id);
        $paymentResponseData = $paymentResponseData['data'];
        $order_id = isset($paymentResponseData['order_id']) ? $paymentResponseData['order_id']:'';
        $payment_reference_id = isset($paymentResponseData['payment_reference_id']) ? $paymentResponseData['payment_reference_id']:'';
        $payment_gateway = isset($paymentResponseData['payment_gateway']) ? $paymentResponseData['payment_gateway']:'';
        $currency_code = isset($paymentResponseData['currency']) ? strtoupper($paymentResponseData['currency']):'';
        $amount = isset($paymentResponseData['amount']) ? $paymentResponseData['amount']:'';
        $currecny_detail = Currency::where(['code'=>$currency_code])->first();
        $currency_id = isset($currecny_detail->id) ? $currecny_detail->id:'';

        $fee = ($amount/100) * $this->percentage_fee;
        $total_fee = $fee + $this->fixed_fee;
        // $amount = $amount + $total_fee;


        $deposit_fee = $total_fee;
        $wallet = Wallet::where(['user_id'=>$user->id,'currency_id'=>$currency_id])->first();
        $trx = Money::getTrx();
        $wallet->fiat+= $amount;
        Transaction::create([
            'user_id' =>  $user->id,
            'entity_id'   =>  $user->id,
            'request_id'=>isset($order_id) ? $order_id:'',
            'transactionable_id'=>isset($trx) ? $trx:'',
            'entity_name' =>  $wallet->currency->name,
            'transaction_state_id'  =>  1, // waiting confirmation
            'money_flow'    => '+',
            'activity_title'    =>  'Payment Link',
            'currency_id' =>  $wallet->currency->id,
            'currency_symbol' =>  $wallet->currency->symbol,
            'thumb' =>  $wallet->currency->thumb,
            'gross' =>  $amount,
            'fee'   =>  $deposit_fee,
            'net'   =>  $amount,
            'balance'   =>  $wallet->fiat,
        ]);
        $wallet->save();
    }
    public function payment_gatepay_process_payment()
    {
        $paytmService       = new PaytmService();
        $instamojoService   = new InstamojoService();
        $iyzicoService      = new IyzicoService();
        $paypalService      = new PaypalService();
        $paystackService      = new PaystackService();
        $razorpayService      = new RazorpayService();
        $stripeService      = new StripeService();
        $authorizeNetService = new AuthorizeNetService();
        $bitPayService = new BitPayService();
        // $mercadopagoService = new MercadopagoService();
        $mercadopagoService = '';
        $payUmoneyService = new PayUmoneyService();
        $mollieService = new MollieService();
        // $mollieService = '';
        $ravepayService = new RavepayService();
        $pagseguroService = new PagseguroService();
        $strowalletService = new StrowalletService();
        $paymentProcess     = new PaymentProcess(
            $paytmService,
            $instamojoService,
            $iyzicoService,
            $paypalService,
            $paystackService,
            $razorpayService,
            $stripeService,
            $authorizeNetService,
            $bitPayService,
            $mercadopagoService,
            $payUmoneyService,
            $mollieService,
            $ravepayService,
            $pagseguroService
        );
        $gump = new GUMP();
        if (isset($_POST) && count($_POST) > 0) 
        {
            $insertData = $gump->sanitize($_POST);
            $validation = GUMP::is_valid($insertData, array(
                //'amount'        => 'required|numeric|min_numeric,0',
                'paymentOption' => 'required'
            ));

            $paymentOption = $insertData['paymentOption'];

            // Check if iyzico or authorize-net payment method is used then check iyzico or authorize-net form data like
            // amount, option, cardname, card number, expiry month, expiry year, cvv etc and validate it
            if ($paymentOption == 'iyzico' or $paymentOption == 'authorize-net') {
                $validation = GUMP::is_valid($insertData, array(
                    //'amount'        => 'required|numeric',
                    'paymentOption' => 'required',
                    'cardname'     => 'required',
                    'cardnumber'   => 'required',
                    'expmonth'     => 'required',
                    'expyear'      => 'required',
                    'cvv'          => 'required'
                ));
            }

            // Check server side validation success then process for next step
            if ($validation === true) {
                if($paymentOption == 'strowallet')
                {
                    $paymentData = $strowalletService->proccesPayment($insertData,url('/payment_gatepay_cancel'));
                    $paymentData['paymentOption'] = $paymentOption;
                    echo json_encode($paymentData);exit();
                }
                // Then send data to payment process service for process payment
                // This service will return payment data
                $paymentData = $paymentProcess->getPaymentData($insertData);

                // set select payment option in return paymentData array
                $paymentData['paymentOption'] = $paymentOption;

                //on success paytm response
                if ($paymentOption == 'paytm') {
                    // If paytm payment method are selected then get payment merchant form
                    $paymentData['merchantForm'] = getPaytmMerchantForm($paymentData);

                    // return payment array on ajax request
                    echo json_encode($paymentData);

                // on success instamojo, paystack, stripe, razorpay, iyzico & paypal response
                //} else if () {
                } elseif ($paymentOption == 'instamojo'
                    || $paymentOption == 'paystack'
                    || $paymentOption == 'iyzico'
                    || $paymentOption == 'paypal'
                    || $paymentOption == 'stripe'
                    || $paymentOption == 'authorize-net'
                    || $paymentOption == 'bitpay'
                    || $paymentOption == 'mercadopago'
                    || $paymentOption == 'payumoney'
                    || $paymentOption == 'mollie'
                    || $paymentOption == 'ravepay'
                    || $paymentOption == 'pagseguro'
                ) {
                    // return payment array on ajax request
                    echo json_encode($paymentData);
                } elseif ($paymentOption == 'razorpay') {
                    echo json_encode(array_values($paymentData)[0]);
                }
            } else {
                // If Validation errors occurred then show it on the form
                $validationMessage = [];

                // get collection of validation messages
                foreach ($validation as $valid) {
                    $validationMessage['validationMessage'][] = strip_tags($valid);
                }

                // return validation array on ajax request
                echo json_encode($validationMessage);

                exit();
            }
        }
        // $data['request_data'] = $request;
        // return view('gatepay.payment-process',$data);
    }
}