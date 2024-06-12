<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Auth;
use App\Models\CurrencyExchangeRate;
use App\Models\ExchangeTransactions;
use App\Models\Transaction;
use App\Models\Currency;
use App\Models\Wallet;
use App\Helpers\Money;
class ExchangeCurrencyController extends Controller
{
  public function exchangeCurrencyForm()
  {
  	$currencies = Currency::all();
  	return view('exchange_currency.exchange_currency',['currencies'=>$currencies]);
  }
  public function currencyExchange(Request $request)
  {
    $first_curruncy_id = $request->from_currency_id;
    $second_curruncy_id = $request->to_currency_id;
    $amount = $request->amount;
    $data = CurrencyExchangeRate::where(['first_currency_id'=>$first_curruncy_id])->where(['second_currency_id'=>$second_curruncy_id])->first();
    if($data)
    {
      $exchange_rate = isset($data->exchanges_to_second_currency_value) ? $data->exchanges_to_second_currency_value:'';
      $total_amount = $exchange_rate * $amount;
      return response()->json(['total_amount'=>$total_amount,'exchange_rate'=>$exchange_rate]);exit();
    }
  }
  public function saveDetail(Request $request)
  {
    $amount = $request->amount;
    $total_amount = $request->total_amount;
    if($amount <= 0)
    {
      flash(__("Invalid amount!"),'error');
      return back();
    }
    $from_currency_id = $request->from_currency_id;
    $to_currency_id = $request->to_currency_id;
    $from_currency = Currency::where(['id'=>$from_currency_id])->first();
    $userWallet = Wallet::where(['user_id'=>auth()->user()->id,'currency_id'=>$from_currency_id])->first();
    if(empty($userWallet))
    {
      flash(__("From currency does not existing!"),'error');
      return back();
    }
    $to_currency = Currency::where(['id'=>$to_currency_id])->first();
    $to_userWallet = Wallet::where(['user_id'=>auth()->user()->id,'currency_id'=>$to_currency_id])->first();
    if(empty($to_userWallet))
    {
      flash(__("To currency does not existing!"),'error');
      return back();
    }
    if($userWallet->fiat < $amount)
    {
      flash(__("Insufficient  Balance !!"),'error');
      return back();
    }
    // $discount_percentage_amount = $this->discount_percentage;
    // $discount_amount = (($amount/100) * $discount_percentage_amount);
    $total_discount = 0;
    $amount = ($amount - $total_discount);
    $userWallet->fiat = $userWallet->fiat - $amount;
    $userWallet->save();
    $fee = 0;
    $user = auth()->user();
    $trx = Money::getTrx();
    $transaction = new Transaction();
    $transaction->user_id = $user->id;
    $transaction->request_id = $trx;
    $transaction->transactionable_id = $trx;
    $transaction->transactionable_type = 'App\Models\ExchangeTransactions';
    $transaction->entity_id = $user->id;
    $transaction->entity_name = isset($user->name) ? $user->name:'';
    $transaction->transaction_state_id = 1;
    $transaction->money_flow = '-';
    $transaction->thumb = isset($user->avatar) ? $user->avatar:'';
    $transaction->currency_id = $from_currency->id;
    $transaction->currency = $from_currency->code;
    $transaction->currency_symbol = isset($from_currency->symbol) ? $from_currency->symbol:'';
    $transaction->activity_title = 'Currency Exchange';
    $transaction->gross = $amount;
    $transaction->fee = $fee;
    $transaction->net = ($amount + $fee);
    $transaction->balance = $userWallet->fiat;
    $transaction->save();

    $to_userWallet->fiat = $to_userWallet->fiat + $total_amount;
    $to_userWallet->save();
    $trx = Money::getTrx();
    $fee = 0;
    $to_transaction = new Transaction();
    $to_transaction->user_id = $user->id;
    $to_transaction->request_id = $trx;
    $to_transaction->transactionable_id = $trx;
    $to_transaction->transactionable_type = 'App\Models\ExchangeTransactions';
    $to_transaction->entity_id = $user->id;
    $to_transaction->entity_name = isset($user->name) ? $user->name:'';
    $to_transaction->transaction_state_id = 1;
    $to_transaction->money_flow = '+';
    $to_transaction->thumb = isset($user->avatar) ? $user->avatar:'';
    $to_transaction->currency_id = $to_currency->id;
    $to_transaction->currency = $to_currency->code;
    $to_transaction->currency_symbol = isset($to_currency->symbol) ? $to_currency->symbol:'';
    $to_transaction->activity_title = 'Currency Exchange';
    $to_transaction->gross = $total_amount;
    $to_transaction->fee = $fee;
    $to_transaction->net = ($total_amount + $fee);
    $to_transaction->balance = $to_userWallet->fiat;
    $to_transaction->save();

    $currency_exchange = new ExchangeTransactions;
    $currency_exchange->user_id =Auth::user()->id;
    $currency_exchange->from_currency_id = $from_currency_id;
    $currency_exchange->to_currency_id = $to_currency_id;
    $currency_exchange->amount = $amount;
    $currency_exchange->exchange_rate = $request->exchange_rate;
    $currency_exchange->total_amount = $total_amount;
    $currency_exchange->save();
    flash(__("saved successfully"),'success');
    return back();
  }
  public function exchange_currency_list()
  {
    $exchangeCurrenyDetail = ExchangeTransactions::where(['user_id'=>Auth::user()->id])->with('first_currency_name','second_currency_name')->latest()->paginate(10);
    $page_title = "Exchange Currency";
    return view('exchange_currency.exchange_currencies_list',['exchangeCurrenyDetail'=>$exchangeCurrenyDetail,'page_title'=>$page_title]);
  }
}
?>