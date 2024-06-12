<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransferMethod;
use Auth;
use App\Models\Wallet;
use App\Models\Currency;

class WalletController extends Controller
{
    

    public function showTransferMethods(Request $request, $lang, $currency_id){
        if (Auth::check()) {
           
        	$transfer_methods = TransferMethod::where('is_active', 1)->where('is_hidden', 0)->where('is_system', 0)->where('currency_id',$currency_id)->paginate('10');
          
            $currency = Currency::where('id', $currency_id)->first();
            if($transfer_methods->total() == 0){
                flash(__('contact admin to add a transfer method for the currency '. $currency->name), 'danger');
                return back();
            }
        	return view('wallet.transfer_methods')->with('methods', $transfer_methods)->with('currency', $currency);
             // The user is logged in...
        }
        return redirect(app()->getLocale().'/');

    }

    public function showCreateMethodForm(Request $request, $lang, $method_id){
        if (Auth::check()) {
           
    	   $method = TransferMethod::where('id', $method_id)->first();
    	   return view('wallet.create_wallet')->with('method', $method);
        
        }
        return redirect(app()->getLocale().'/');
    }

    public function createMethod(Request $request){

        $this->validate($request,[
            'transfer_method_id' => 'required|exists:transfer_methods,id',
            'accont_identifier_mechanism_id'    =>  'required'
        ]);

        $wallet = Auth::user()->currentWallet();
        $transferMethod = TransferMethod::where('id', $request->transfer_method_id)->first();

        $wallet->TransferMethods()->attach($transferMethod, ['user_id'=>Auth::user()->id,'adress' => $request->accont_identifier_mechanism_id]);

        return redirect(app()->getLocale().'/payout/'.$wallet->id);
    }

    public function showCurrencies(Request $request, $lang){
        if (Auth::check()) {

            // $wallets = Wallet::with('TransferMethods')->where('user_id', Auth::user()->id)->get();
            
            // $ids = [];
            // $currencies = [];
            
            // foreach ($wallets as $wallet) {
            //     foreach($wallet->TransferMethods as $tm){
            //         array_push($ids, $tm->currency->id);
            //     }
            // }


            // $currencies = Currency::whereNotIn('id', $ids)->paginate(10);

            $currencies = Currency::orderby('is_crypto')->paginate(10);
            
            if(count($currencies) <= 0){
                dd('contact admin to add some currencies to work with');
            }
            return  view('wallet.currencies')->with('currencies', $currencies);
        }

        return redirect(app()->getLocale().'/');
    }

    public function createWallet(Request $request, $lang, $currency_id){
         if (Auth::check()) {

            $currency = Currency::where('id', $currency_id)->first();

            $currency ?? dd('currency not found');

            $wallet = Wallet::where('currency_id', $currency_id)->where('user_id', Auth::user()->id)->first();

            if($wallet != null){
                flash('You already have activated your ' . $currency->name . ' wallet', 'info');
                return back();
            }

            $method = TransferMethod::where('currency_id', $currency_id)->where('is_system', 1)->first();

            $method ?? $method = TransferMethod::create([
                'currency_id' => $currency_id,
                'name' => 'System_'.$currency->name.'_method',
                'accont_identifier_mechanism' => 'placeholder',
                'how_to_deposit' => 'placeholder',
                'how_to_withdraw' => 'placeholder',
                'days_to_process_transfer' => 1,
                'is_active' => 0,
                'thumbnail' => 'placeholder',
                'deposit_percentage_fee' => 10,
                'deposit_fixed_fee' => 1,
                'withdraw_percentage_fee' => 10,
                'withdraw_fixed_fee' => 1,
                'mobile_thumbnail' => 'placeholder',
                'merchant_percentage_fee' => 10,
                'merchant_fixed_fee' => 1,
                'is_hidden' => 1,
                'is_system' => 1
            ]);

            $wallet = wallet::create([
                'is_crypto' =>  $currency->is_crypto,
                'user_id'   => Auth::user()->id,
                'amount'    =>  0,
                'fiat'    =>  0,
                'payout_fiat'    =>  0,
                'currency_id'   => $currency->id,
                'transfer_method_id'    => $method->id,
                'accont_identifier_mechanism_value' =>  'delete address',
            ]);
            $wallet->TransferMethods()->attach($method, ['user_id'=>Auth::user()->id,'adress' => $request->accont_identifier_mechanism_id]);

            

            Auth::user()->currency_id = $currency->id;
            Auth::user()->wallet_id = $wallet->id;
            Auth::user()->save();
          

            return redirect(app()->getLocale().'/home');

         }else{
            return redirect('/en');
         }

    	
    }
}
