<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\transactionCompletedUserNotificationEmail;
use App\Models\Deposit;
use App\Models\Merchant;
use App\Models\Purchase;
use App\Models\PurchaseRequest;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\TransactionState;
use App\User;
use Illuminate\Http\Request;
use App\Models\TransactionLogs;

class MerchantPaymentApiController extends Controller
{
    public function validateTransaction(Request $request){
       
        $logs = new TransactionLogs();
        $logs->user_id = $request->user_id ?? '';
        $logs->ref = $request->token ?? '';
        $logs->ip = \Request::ip();
        $logs->user_agent = \Request::server('HTTP_USER_AGENT');
        $logs->referrer = \Request::header('referer') ?? '';
        $logs->page_link = \Request::url();
        $logs->amount = $request->sourceAmount ?? '0.00';
        
        $type = $request->type ?? '';

        if (empty($request->merchant_key)) {
            $logs->action =  'Payment Using QR';
            $logs->message = 'No Merchant Key Provided';
            $logs->save();

            return response()->json([
                'status' => false,
                'error_message' => 'No merchant key provided',
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }
        if (empty($request->token)) {
            $logs->action =  'Payment Using QR';
            $logs->message = 'No Token Provided';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => 'No token provided',
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        $merchant = Merchant::with('Currency')->where('merchant_key', $request->merchant_key)->first();
        if (empty($merchant)) 
        {
            $logs->action =  'Payment Using QR';
            $logs->message = 'Merchant Not Found';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => 'Merchant Not Found !, Please check your merchant_key and try again',
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        $purchaseRequest = PurchaseRequest::where('ref', $request->token)->first();

        $merchant = Merchant::with('User')
            ->where('merchant_key', $request->merchant_key)
            ->first();
       
            
        if($purchaseRequest->merchant_key != $merchant->merchant_key) {
            $logs->action =  'Payment Using QR';
            $logs->message = 'Merchant Key Mismatched';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => 'Merchant key mismatched',
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        $depositTransaction = Transaction::query()
            ->where('transactionable_type', Deposit::class)
            ->where('request_id', $purchaseRequest->id)
            ->first();

        if (empty($depositTransaction)) {
            $logs->action =  'Deposit Transaction Failed';
            $logs->message = 'No Deposit Transaction Found';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => 'No deposit transaction found. Please try again later',
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        $saleTransaction = Transaction::query()
            ->where('transactionable_type', Sale::class)
            ->where('request_id', $purchaseRequest->id)
            ->first();

        if (empty($saleTransaction)) {
            $logs->action =  'Sale Transaction Failed';
            $logs->message = 'No Sale Transaction Found';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => 'No sale transaction found. Please try again later',
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        $purchaseTransaction = Transaction::query()
            ->where('transactionable_type', Purchase::class)
            ->where('request_id', $purchaseRequest->id)
            ->first();

        
        if (empty($purchaseTransaction)) {
            $logs->action =  'Purchase Transaction Failed';
            $logs->message = 'No Purchase Transaction Found';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => 'No purchase transaction found. Please try again later',
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        $depositStatus = $depositTransaction->transaction_state_id;
        $saleStatus = $saleTransaction->transaction_state_id;
        $purchaseStatus = $purchaseTransaction->transaction_state_id;

        // $transactionStatus = '';
        $errorMsg = '';
        if($depositStatus == 2) {
            $errorMsg = 'Deposit Declined. Please try again later';
            $logs->action =  'Deposit';
            $logs->message = 'Deposit Declined';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => $errorMsg,
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        if($depositStatus == 3) {
            $errorMsg = 'Deposit Pending. Please try again later';
            $logs->action =  'Deposit';
            $logs->message = 'Deposit Pending';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => $errorMsg,
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }
        if($saleStatus == 2) {
            $errorMsg = 'Sale Declined. Please try again later';
            $logs->action =  'Sale';
            $logs->message = 'Sale Declined';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => $errorMsg,
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        if($saleStatus == 3) {
            $errorMsg = 'Sale Pending. Please try again later';
            $logs->action =  'Sale';
            $logs->message = 'Sale Pending';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => $errorMsg,
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }
        if($purchaseStatus == 2) {
            $errorMsg = 'Purchase Declined. Please try again later';
            $logs->action =  'Purchase';
            $logs->message = 'Purchase Declined';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => $errorMsg,
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        if($purchaseStatus == 3) {
            $errorMsg = 'Purchase Pending. Please try again later';
            $logs->action =  'Purchase';
            $logs->message = 'Purchase Pending';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => $errorMsg,
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }
       

        if (!empty($merchant)){
            $amount = $request->sourceAmount ?? 0;
           

            if ($request->email && $request->email != '') {
                $user = User::where('email', $request->email)->first();
            }else{
                $user = User::where('id', $merchant->user_id)->first();
            }

            $user->sourceAmount = $amount;
            $user->token = $request->token;

            try {
                Mail::send(new transactionCompletedUserNotificationEmail($user));
            } catch (\Exception $e) {}
        
        }
       

        $logs->action =  'Payment Using QR';
        $logs->message = 'Payment Completed';
        $logs->save();

        if ($type == '1') {
            return response()->json([
                'status' => true,
                'transaction_status' => 'Completed',
                'success_message' => 'Transaction completed',
            ]);
        }else{

            $reference = $request->token;
            $request = PurchaseRequest::where('ref',$reference)->first();
            if ($request) {
                $deposit = Deposit::where('request_id',$request->id)->first();
            }
          
            if ($deposit) {
                return response()->json([
                    'status' => true,
                    'transaction_status' => 'Completed',
                    'success_message' => 'Transaction completed',
                    'txn_details(UTR)' =>  $deposit->ag_bank_reference_no ?? '',
                    'amount' => $deposit->net ?? ''

                ]);
           }

        }
    }

    public function validateQrTransaction(Request $request){
       
        $logs = new TransactionLogs();
        $logs->user_id = $request->user_id ?? '';
        $logs->ref = $request->token ?? '';
        $logs->ip = \Request::ip();
        $logs->user_agent = \Request::server('HTTP_USER_AGENT');
        $logs->referrer = \Request::header('referer') ?? '';
        $logs->page_link = \Request::url();
        $logs->amount = $request->sourceAmount ?? '0.00';
        
        $type = $request->type ?? '';

    
        if (empty($request->token)) {
            $logs->action =  'Payment Using QR';
            $logs->message = 'No Token Provided';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => 'No token provided',
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        $purchaseRequest = PurchaseRequest::where('ref', $request->token)->first();

    
        $depositTransaction = Transaction::query()
            ->where('transactionable_type', Deposit::class)
            ->where('request_id', $purchaseRequest->id)
            ->first();

        if (empty($depositTransaction)) {
            $logs->action =  'Deposit Transaction Failed';
            $logs->message = 'No Deposit Transaction Found';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => 'No deposit transaction found. Please try again later',
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        $saleTransaction = Transaction::query()
            ->where('transactionable_type', Sale::class)
            ->where('request_id', $purchaseRequest->id)
            ->first();

        if (empty($saleTransaction)) {
            $logs->action =  'Sale Transaction Failed';
            $logs->message = 'No Sale Transaction Found';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => 'No sale transaction found. Please try again later',
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        $purchaseTransaction = Transaction::query()
            ->where('transactionable_type', Purchase::class)
            ->where('request_id', $purchaseRequest->id)
            ->first();

        
        if (empty($purchaseTransaction)) {
            $logs->action =  'Purchase Transaction Failed';
            $logs->message = 'No Purchase Transaction Found';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => 'No purchase transaction found. Please try again later',
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        $depositStatus = $depositTransaction->transaction_state_id;
        $saleStatus = $saleTransaction->transaction_state_id;
        $purchaseStatus = $purchaseTransaction->transaction_state_id;

        // $transactionStatus = '';
        $errorMsg = '';
        if($depositStatus == 2) {
            $errorMsg = 'Deposit Declined. Please try again later';
            $logs->action =  'Deposit';
            $logs->message = 'Deposit Declined';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => $errorMsg,
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        if($depositStatus == 3) {
            $errorMsg = 'Deposit Pending. Please try again later';
            $logs->action =  'Deposit';
            $logs->message = 'Deposit Pending';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => $errorMsg,
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }
        if($saleStatus == 2) {
            $errorMsg = 'Sale Declined. Please try again later';
            $logs->action =  'Sale';
            $logs->message = 'Sale Declined';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => $errorMsg,
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        if($saleStatus == 3) {
            $errorMsg = 'Sale Pending. Please try again later';
            $logs->action =  'Sale';
            $logs->message = 'Sale Pending';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => $errorMsg,
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }
        if($purchaseStatus == 2) {
            $errorMsg = 'Purchase Declined. Please try again later';
            $logs->action =  'Purchase';
            $logs->message = 'Purchase Declined';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => $errorMsg,
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }

        if($purchaseStatus == 3) {
            $errorMsg = 'Purchase Pending. Please try again later';
            $logs->action =  'Purchase';
            $logs->message = 'Purchase Pending';
            $logs->save();
            return response()->json([
                'status' => false,
                'error_message' => $errorMsg,
                'success_message' => '',
                'transaction_status'=> null,
            ]);
        }
       
       if ($request->email && $request->email != '') {
            $user = User::where('email', $request->email)->first();
            $amount = $request->sourceAmount ?? 0;
            $user->sourceAmount = $amount;
            $user->token = $request->token;
            
            try {
                Mail::send(new transactionCompletedUserNotificationEmail($user));
            } catch (\Exception $e) {}
        }
       

        $logs->action =  'Payment Using QR';
        $logs->message = 'Payment Completed';
        $logs->save();

        if ($type == '1') {
            return response()->json([
                'status' => true,
                'transaction_status' => 'Completed',
                'success_message' => 'Transaction completed',
            ]);
        }else{

            $reference = $request->token;
            $request = PurchaseRequest::where('ref',$reference)->first();
            if ($request) {
                $deposit = Deposit::where('request_id',$request->id)->first();
            }
          
            if ($deposit) {
                return response()->json([
                    'status' => true,
                    'transaction_status' => 'Completed',
                    'success_message' => 'Transaction completed',
                    'txn_details' =>  "{'bank_txn_ref':  $deposit->ag_bank_reference_no ?? ''}",
                ]);
           }

        }
    }
}