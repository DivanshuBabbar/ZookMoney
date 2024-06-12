<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Auth;
use Storage;
use App\User;
use App\Models\DepositMethod;
use App\Models\Currency;
use App\Models\Wallet;
use App\Models\Deposit;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Support\Facades\DB;
use App\Helpers\Money;
use Validator;
use Illuminate\Http\Request;
use App\Mail\GiftCardEmail;
use Illuminate\Pagination\LengthAwarePaginator;

class DepositApiController extends Controller
{
    public function get_deposit_payment_method ($user_id=null)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user)) 
        {
           $depositMethod =  DepositMethod::whereStatus(1)->get();
           return response()->json(['success'=> true,'data'=>$depositMethod]);
        }
        else
        {
           return response()->json(['success'=> true,'message'=>'User Not exist']);
        }
    }
    public function deposit_api($user_id=null,request $request)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user))
        {
            $payment_method_id = DepositMethod::findOrFail($request->deposit_method_id);
            $currency_id = isset($payment_method_id->currency_id) ? $payment_method_id->currency_id:'';
            $currency = Currency::findOrFail($currency_id);
            $currency_symbol = isset($currency->symbol) ? $currency->symbol:'';
            $wallet = Wallet::where(['user_id'=>$user->id])->where(['currency_id'=>$currency_id])->first();
            if(!$wallet)
            {
                return response()->json(['success'=> true,'message'=>'Wallet not found!']);
            }
            $wallet_id = isset($wallet->id) ? $wallet->id:'';
            $trx = Money::getTrx();
            $method = new Deposit;
            if($request->hasFile('transaction_receipt'))
            {
                $file = $request->file('transaction_receipt');
                $ex = $file->getClientOriginalExtension();
                $filename = time().'.'.$ex;
                $file->move('assets/images', $filename);
                $method->transaction_receipt = $filename;
            }
            $method->user_id = $user->id;
            $method->unique_transaction_id = $trx;
            $method->transaction_receipt_ref_no = isset($request->transaction_receipt_ref_no) ? $request->transaction_receipt_ref_no : null;
            $method->net = $request->amount;
            $method->gross = $request->amount;
            $method->deposit_method_id = $request->deposit_method_id;
            $method->currency_id = $currency_id;
            $method->currency_symbol = $currency_symbol;
            $method->wallet_id = $wallet_id;
            $saved = $method->save();
            if($saved)
            {
                return response()->json(['success'=> true,'message'=>'Deposit request submitted successfully!']);
            }
            else
            {
                return response()->json(['success'=> false,'message'=>'Error please try again']);
            }
        }
        else
        {
            return response()->json(['success'=> false,'message'=>'User not exist']);
        }
    }
}