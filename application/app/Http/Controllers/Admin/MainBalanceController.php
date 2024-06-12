<?php

namespace App\Http\Controllers\Admin;
use Auth;
use App\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class MainBalanceController extends Controller{

   //===================================== MAIN TO HOLD ==================================================

    public function index(Request $request){
        $user_id = $request->input('id');
        $currency_id = $request->input('currency_id');
        $balance = Wallet::where('user_id', $user_id)->where('currency_id', $currency_id)->value('fiat');
        $amount = $request->input('amount', 0); 
    
        // Validation
        if ($balance <= 0 ) {
            return response()->json(['message' => 'Low balance', 'status' => '0']);
        }
    
        if (!is_numeric($amount) || $amount <= 0){
            return response()->json(['message' => 'Amount should be a numeric value greater than zero', 'status' => '0']);
        }
    
        if ($amount > $balance) {
            return response()->json(['message' => 'Amount cannot be greater than available balance', 'status' => '0']);
        }
    
        $wallet = Wallet::where('user_id', $user_id)->where('currency_id', $currency_id)->first();
        if(!$wallet){
            return response()->json(['message' => 'Wallet not found', 'status' => '0']);
        }
    
        // Handle hold balance
        $total_hold_balance = $wallet->main_hold_balance ?? 0;
        $total_hold_balance += $amount;
    
        // Update wallet
        $updated_wallet_balance = $wallet->fiat - $amount;
        $wallet->fiat = $updated_wallet_balance;
        $wallet->main_hold_balance = $total_hold_balance;
        $wallet->save();

        $user_save = User::where('id',$user_id)->first();
        if($user_save){
            $user_save->main_hold_balance = $total_hold_balance;
            $user_save->save();
        }

        $srting = strtoupper($this->random_strings(8));
        
        $check_trxn_balance = Transaction::where('user_id', $user_id)->latest()->pluck('balance')->first();
        if ($check_trxn_balance == 0) {
            $total_trxn_balance =  $amount;   
        } else {
            $total_trxn_balance = $check_trxn_balance - $amount;
            $total_trxn_balance = max(0, $total_trxn_balance);
        }

        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->entity_id = $user_id;
        $transaction->entity_name = Auth::user()->name;
        $transaction->transactionable_type = 'App\Models\Main_to_hold';
        $transaction->activity_title = 'Main To Hold Balance';
        $transaction->money_flow = '-';
        $transaction->transaction_state_id = '1';
        $transaction->balance = $total_trxn_balance;
        $transaction->transactionable_id = $srting;
        $transaction->request_id = $srting;
        $transaction->gross = $amount;
        $transaction->fee = 0.00;
        $transaction->net = $amount;
        $transaction->currency_id = $wallet->currency_id;
        $transaction->currency_symbol = ($wallet->currency_id == '14') ? 'INR' : 'USD';
        $transaction->main_hold_balance = $total_hold_balance;
        $transaction->save();

        return response()->json(['message' => 'balance transfered successfully' ,'status'=>'1']);
    }

    //======================================  HOLD TO MAIN ===============================================

    public function hold_balance_index(Request $request){
        $user_id = $request->input('id');
        $currency_id = $request->input('currency_id');
        $balance = Wallet::where('user_id', $user_id)->where('currency_id', $currency_id)->value('main_hold_balance');
        $amount = $request->input('amount', 0); 
    
        // Validation
        if ($balance <= 0 ) {
            return response()->json(['message' => 'Low hold balance  ', 'status' => '0']);
        }
    
        if (!is_numeric($amount) || $amount <= 0){
            return response()->json(['message' => 'Amount should be a numeric value greater than zero', 'status' => '0']);
        }
    
        if ($amount > $balance) {
            return response()->json(['message' => 'Amount cannot be greater than available balance', 'status' => '0']);
        }
    
        $wallet = Wallet::where('user_id', $user_id)->where('currency_id', $currency_id)->first();
        if(!$wallet){
            return response()->json(['message' => 'Wallet not found', 'status' => '0']);
        }

        // Calculate total hold balance
        $total_hold_balance = $wallet->main_hold_balance > 0 ? $wallet->main_hold_balance - $amount : 0;

        // Update wallet balance
        $updated_wallet_balance = $wallet->fiat + $amount;

        // Update wallet and save
        $wallet->fiat = $updated_wallet_balance;
        $wallet->main_hold_balance = $total_hold_balance;
        $wallet->save();

        // Update user main_hold_balance
        $user = User::find($user_id);
        if($user){
            $user->main_hold_balance = $total_hold_balance;
            $user->save();
        }

        $srting = strtoupper($this->random_strings(8));

        $check_trxn_balance = Transaction::where('user_id', $user_id)->latest()->pluck('balance')->first();
        if ($check_trxn_balance == 0) {
            $total_trxn_balance =  $amount;   
        } else {
            $total_trxn_balance = $check_trxn_balance - $amount;
            $total_trxn_balance = max(0, $total_trxn_balance);
        }

        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->entity_id = $user_id;
        $transaction->entity_name = Auth::user()->name;
        $transaction->transactionable_type = 'App\Models\Hold_to_main';
        $transaction->activity_title = 'Hold To Main Balance';
        $transaction->money_flow = '-';
        $transaction->transaction_state_id = '1';
        $transaction->balance = $total_trxn_balance;
        $transaction->transactionable_id = $srting;
        $transaction->request_id = $srting;
        $transaction->gross = $amount;
        $transaction->fee = 0.00;
        $transaction->net = $amount;
        $transaction->currency_id = $wallet->currency_id;
        $transaction->currency_symbol = ($wallet->currency_id == '14') ? 'INR' : 'USD';
        $transaction->main_hold_balance = $total_hold_balance;
        $transaction->save();

        return response()->json(['message' => 'balance transfered successfully' ,'status'=>'1']);

    }

    public static function random_strings($length_of_string)
    {
        // md5 the timestamps and returns substring
        // of specified length
        return substr(md5(time()), 0, $length_of_string);
    }

    // ===================== PAYOUT TO HOLD ==============================================================

    public function payouthold_balance_index(Request $request){
        $user_id = $request->input('id');
        $currency_id = $request->input('currency_id');
        $balance = Wallet::where('user_id', $user_id)->where('currency_id', $currency_id)->value('payout_fiat');
        $amount = $request->input('amount', 0); 
    
        // Validation
        if ($balance <= 0 ) {
            return response()->json(['message' => 'Low balance', 'status' => '0']);
        }
    
        if (!is_numeric($amount) || $amount <= 0){
            return response()->json(['message' => 'Amount should be a numeric value greater than zero', 'status' => '0']);
        }
    
        if ($amount > $balance) {
            return response()->json(['message' => 'Amount cannot be greater than available balance', 'status' => '0']);
        }
    
        $wallet = Wallet::where('user_id', $user_id)->where('currency_id', $currency_id)->first();
        if(!$wallet){
            return response()->json(['message' => 'Wallet not found', 'status' => '0']);
        }

        // Handle hold balance
        $total_hold_balance = $wallet->payout_hold_balance ?? 0;
        $total_hold_balance += $amount;
    
        // Update wallet
        $updated_wallet_balance = $wallet->payout_fiat - $amount;
        $wallet->payout_fiat = $updated_wallet_balance;
        $wallet->payout_hold_balance = $total_hold_balance;
        $wallet->save();

        $user_save = User::where('id',$user_id)->first();
        $updated_user_payout_balance = $user_save->payout_balance - $amount;
        if($user_save){
            $user_save->payout_hold_balance = $total_hold_balance;
            $user_save->payout_balance = $updated_user_payout_balance;
            $user_save->save();
        }

        $srting = strtoupper($this->random_strings(8));
        
        $check_trxn_balance = Transaction::where('user_id', $user_id)->latest()->pluck('balance')->first();
        if ($check_trxn_balance == 0) {
            $total_trxn_balance =  $amount;   
        } else {
            $total_trxn_balance = $check_trxn_balance - $amount;
            $total_trxn_balance = max(0, $total_trxn_balance);
        }

        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->entity_id = $user_id;
        $transaction->entity_name = Auth::user()->name;
        $transaction->transactionable_type = 'App\Models\Payout_to_hold';
        $transaction->activity_title = 'Payout To Hold Balance';
        $transaction->money_flow = '-';
        $transaction->transaction_state_id = '1';
        $transaction->balance = $total_trxn_balance;
        $transaction->transactionable_id = $srting;
        $transaction->request_id = $srting;
        $transaction->gross = $amount;
        $transaction->fee = 0.00;
        $transaction->net = $amount;
        $transaction->currency_id = $wallet->currency_id;
        $transaction->currency_symbol = ($wallet->currency_id == '14') ? 'INR' : 'USD';
        $transaction->payout_hold_balance = $total_hold_balance;
        $transaction->save();

        return response()->json(['message' => 'balance transfered successfully' ,'status'=>'1']);
    }

   //===================================  HOLD TO PAYOUT  ================================================

    public function holdpayout_balance_index(Request $request){
        $user_id = $request->input('id');
        $currency_id = $request->input('currency_id');
        $balance = Wallet::where('user_id', $user_id)->where('currency_id', $currency_id)->value('payout_hold_balance');
        $amount = $request->input('amount', 0); 
    
        // Validation
        if ($balance <= 0 ) {
            return response()->json(['message' => 'Low hold balance', 'status' => '0']);
        }
    
        if (!is_numeric($amount) || $amount <= 0){
            return response()->json(['message' => 'Amount should be a numeric value greater than zero', 'status' => '0']);
        }
    
        if ($amount > $balance) {
            return response()->json(['message' => 'Amount cannot be greater than available balance', 'status' => '0']);
        }
    
        $wallet = Wallet::where('user_id', $user_id)->where('currency_id', $currency_id)->first();
        if(!$wallet){
            return response()->json(['message' => 'Wallet not found', 'status' => '0']);
        }

        // Calculate total hold balance
        $total_hold_balance = $wallet->payout_hold_balance > 0 ? $wallet->payout_hold_balance - $amount : 0;

        // Update wallet balance
        $updated_wallet_balance = $wallet->payout_fiat + $amount;

        // Update wallet and save
        $wallet->payout_fiat = $updated_wallet_balance;
        $wallet->payout_hold_balance = $total_hold_balance;
        $wallet->save();

        // Update user payout_hold_balance
        $user = User::find($user_id);
        $updated_user_payout_balance = $user->payout_balance + $amount;
        if($user){
            $user->payout_hold_balance = $total_hold_balance;
            $user->payout_balance = $updated_user_payout_balance;   
            $user->save();
        }

        $srting = strtoupper($this->random_strings(8));

        $check_trxn_balance = Transaction::where('user_id', $user_id)->latest()->pluck('balance')->first();
        if ($check_trxn_balance == 0) {
            $total_trxn_balance =  $amount;   
        } else {
            $total_trxn_balance = $check_trxn_balance - $amount;
            $total_trxn_balance = max(0, $total_trxn_balance);
        }

        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->entity_id = $user_id;
        $transaction->entity_name = Auth::user()->name;
        $transaction->transactionable_type = 'App\Models\Hold_to_payout';
        $transaction->activity_title = 'Hold To Payout Balance';
        $transaction->money_flow = '-';
        $transaction->transaction_state_id = '1';
        $transaction->balance = $total_trxn_balance;
        $transaction->transactionable_id = $srting;
        $transaction->request_id = $srting;
        $transaction->gross = $amount;
        $transaction->fee = 0.00;
        $transaction->net = $amount;
        $transaction->currency_id = $wallet->currency_id;
        $transaction->currency_symbol = ($wallet->currency_id == '14') ? 'INR' : 'USD';
        $transaction->payout_hold_balance = $total_hold_balance;
        $transaction->save();

        return response()->json(['message' => 'balance transfered successfully' ,'status'=>'1']);
    }
}
