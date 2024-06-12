<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Auth;
use Storage;
use App\User;
use App\Models\Send;
use App\Models\Currency;
use App\Models\Receive;
use App\Models\Country;
use App\Models\MainCurrency;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet;
use App\Models\Vcard;
use App\Models\ExchangeTransactions;
use App\Helpers\Money;
use App\Models\Transaction;
use App\Models\GeneralSetting;
// use Validator;

use Illuminate\Http\Request;
use App\Mail\GiftCardEmail;
use App\Models\Merchant;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->currency = "usd";
        $this->percentage = 1;
        $this->fee = 1;
        $this->cardtypemaster = "master";
        $this->public_key = "VN21ZNMSYCOOJK56TA1KHCRE2ZTMWG";
        $this->fund_fee_percentage = 1;
        $general_settings = GeneralSetting::first();
        $fee = isset($general_settings->gift_card_fee) ? $general_settings->gift_card_fee:0;
        $admin_email = isset($general_settings->support_email) ? $general_settings->support_email:'';
        $this->gift_card_fee_percentage = $fee;
        $this->admin_email = $admin_email;
    }
    public function index($user_id=null)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user)) 
        {
            $currencies = Currency::get();
            return response()->json(['success'=>true,'data'=>$currencies]);
        }
        else
        {
            return response()->json(['success'=>false,'message'=>'User Not exist']);
        }
    }
    public function send_money(Request $request)
    {

        $merchant_key = $request->merchant_key;
        $currency_code = $request->currency_code;
        $receiver_email = $request->receiver_email;
        $description = $request->description;
        $amount = $request->amount;

        if(empty($merchant_key)) {
            return response()->json([
                'success' => false,
                'message' => 'Provide merchant key!',
            ]);
        }
        if(empty($currency_code)) {
            return response()->json([
                'success' => false,
                'message' => 'Provide currency code!',
            ]);
        }
        if(empty($receiver_email)) {
            return response()->json([
                'success' => false,
                'message' => 'Provide receiver email!',
            ]);
        }

        if(empty($description)) {
            return response()->json([
                'success' => false,
                'message' => 'Provide description!',
            ]);
        }
        if(empty($amount)) {
            return response()->json([
                'success' => false,
                'message' => 'Provide transfer amount!',
            ]);
        }
        
        $merchant = Merchant::with('Currency')->where('merchant_key', $request->merchant_key)->first();

        if (empty($merchant)) {
            return response()->json([
                'success' => false,
                'message' => 'Merchant Not Found !, Please check your merchant_key and try again',
            ]);
        }

        if ($merchant->Currency->code != $request->currency_code) {
            return response()->json([
                'success' => false,
                'message' => 'The Merchant ' . $merchant->name . ' only accepts ' . $merchant->Currency->name . ' [' . $merchant->Currency->code . '] as payment currency',
            ]);
        }

        $login_user = User::find($merchant->user_id);

        if (empty($login_user)) {
            return response()->json(['success' => false, 'message' => 'User Not exist']);
        }

        if ($amount <= 0) {
            return response()->json(['success' => false, 'message' => 'Please insert an amount greater than 0']);
        }
        $auth_wallet = $login_user->currentWallet();
        
        if ($amount > $auth_wallet->amount) {
            return response()->json(['success' => false, 'message' => 'You have insufficient funds to send ' . $amount . ' ' .  $auth_wallet->currency->code .  ' to ' . $receiver_email]);
        }
        
        $currency = Currency::find($auth_wallet->currency_id);
        if ($currency->is_crypto == 1) {
            $precision = 8;
        } else {
            $precision = 2;
        }
        if ((bool)$currency == false) {
            return response()->json(['success' => false, 'message' => 'Wops, something went wrong... looks like we do not support this currency. please contact support if this error persists !']);
        }
        if ($login_user->account_status == 0) {
            return response()->json(['success' => false, 'message' => 'Your account is under a withdrawal request review proccess. please wait for a few minutes and try again']);
        }
        if ($receiver_email == $login_user->email) {
            return response()->json(['success' => false, 'message' => 'You can\'t send money to the same account you are in']);
        }

        if (filter_var($receiver_email, FILTER_VALIDATE_EMAIL)) {

            $validator = Validator::make($request->all(), [
                'amount'    =>  'bail|required|numeric',
                'description'   =>  'bail|required|string',
                'receiver_email' =>  'bail|required|email|exists:users,email',
            ], [
                'receiver_email.exists' => ('The receiver email ') . $receiver_email . __(' is invalid')
            ]);
            
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }

            $receiver = User::where('email', $receiver_email)->first();

        } else {

            $validator = Validator::make($request->all(), [
                'amount'    =>  'bail|required|numeric|between:0,' . $login_user->currentWallet()->amount,
                'description'   =>  'bail|required|string',
                'receiver_email' =>  'bail|required|exists:users,name',
            ], [
                'receiver_email.exists' => ('The receiver username ') . $receiver_email . __(' is invalid')
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }

            $receiver = User::where('name', $receiver_email)->first();
        }
        
        if ($receiver->id == $login_user->id) {
            return response()->json(['success' => false, 'message' => 'Not allowed to send or receive funds from your own account']);
        }
        $receiver_wallet = $receiver->walletByCurrencyId($currency->id);
        if ($receiver_wallet == NULL) {
            return response()->json(['success' => false, 'message' => 'The user ' . $receiver->name . " have not activated a wallet on his account yet !"]);
        }

        $send_fee = 0; //free to send money
        if ($currency->is_crypto == 1) {
            $receive_fee = bcmul('' . (general_setting('mt_percentage_fee') / 100), $amount, $precision);
        } else if ($currency->is_crypto == 0) {
            $receive_fee = bcadd(bcmul('' . (general_setting('mt_percentage_fee') / 100), $amount, $precision), general_setting('mt_fixed_fee'), $precision);
        }
        if (($amount - (float) $receive_fee) < 0) {

            return response()->json(['success' => false, 'message' => 'The minimum amount to send is ' .  bcsub($amount, $receive_fee, $precision)]);
        }

        DB::beginTransaction();
        try {
            $receive = Receive::create([
                'user_id'   =>   $receiver->id,
                'from_id'        => $login_user->id,
                'transaction_state_id'  =>  1, // Approved
                'gross'    =>  $amount,
                'currency_id' =>  $currency->id,
                'currency_symbol' =>  $currency->symbol,
                'fee'   =>  $receive_fee,
                'net'   =>  bcsub($amount, $receive_fee, $precision),
                'description'   =>  $description,
            ]);
            $send = Send::create([
                'user_id'   =>  $login_user->id,
                'to_id'        =>  $receiver->id,
                'transaction_state_id'  =>  1, // waiting confirmation 
                'gross'    =>  $amount,
                'currency_id' =>  $currency->id,
                'currency_symbol' =>  $currency->symbol,
                'fee'   =>  $send_fee,
                'net'   =>  bcsub($amount, $send_fee, $precision),
                'description'   =>  $request->description,
                'receive_id'    =>  $receive->id
            ]);
            $receive->send_id = $send->id;
            $receive->save();

            // receivable transaction
            $receiver->RecentActivity()->save($receive->Transactions()->create([
                'user_id' => $receive->user_id,
                'entity_id'   =>  $receive->id,
                'entity_name' =>  $login_user->email,
                'transaction_state_id'  =>  1, // confirmed
                'money_flow'    => '+',
                'currency_id' =>  $currency->id,
                'thumb' =>  $login_user->avatar,
                'currency_symbol' =>  $currency->symbol,
                'activity_title'    =>  'Money Received',
                'gross' =>  $receive->gross,
                'fee'   =>  $receive->fee,
                'net'   =>  $receive->net,
                'balance'   =>  bcadd( $receiver_wallet->amount , $receive->net, $precision ),
            ]));

            // sendable transaction
            $login_user->RecentActivity()->save($send->Transactions()->create([
                'user_id' =>  $login_user->id,
                'entity_id'   =>  $send->id,
                'entity_name' =>  $receiver->email,
                'transaction_state_id'  =>  1, // confirmed
                'money_flow'    => '-',
                'thumb' =>  $receiver->avatar,
                'currency_id' =>  $currency->id,
                'currency_symbol' =>  $currency->symbol,
                'activity_title'    =>  'Money Sent',
                'gross' =>  $send->gross,
                'fee'   =>  $send->fee,
                'net'   =>  $send->net,
                'balance'   =>  bcsub( $auth_wallet->amount , $send->net, $precision),
            ]));

            $auth_wallet->amount = bcsub ( $auth_wallet->amount , $send->net, $precision ) ;
            $auth_wallet->save();

            $receiver_wallet->amount =  bcadd($receiver_wallet->amount,  $receive->net, $precision ) ;
            $receiver_wallet->save();
        
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Unexpected error occurred!']);
        }
        
        DB::commit();
        return response()->json([
            'success' => true, 
            'message' => 'Transaction success!', 
            'data' => $request->all()
        ]);

    }
    public function get_country($user_id=null)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user)) 
        {
            $countries = Country::all();
            return response()->json(['success'=>true,'data'=>$countries]);
        }
        else
        {
            return response()->json(['success'=>false,'message'=>'User Not exist']);
        }
        
    }
    public function register(request $request)
    {
        $main_currency = MainCurrency::with('currency')->first();
        $currency = $main_currency->currency;
        $this->validate($request, [
            'email' => 'required',
            'name'  =>  'required|unique:users,name',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'password'  =>  'required',
            'phone' =>  'required|phone:US,CA,AF,AL,DZ,AS,AD,AO,AI,AQ,AG,AR,AM,AW,AU,AT,AZ,BS,BH,BD,BB,BY,BE,BZ,BJ,BM,BT,BO,BA,BW,BV,BR,IO,BN,BG,BF,BI,KH,CM,CV,KY,CF,TD,CL,CN,CX,CC,CO,KM,CG,CK,CR,HR,CU,CY,CZ,CD,DK,DJ,DM,DO,TP,EC,EG,SV,GQ,ER,EE,ET,FK,FO,FJ,FI,FR,FX,GF,PF,TF,GA,GM,GE,DE,GH,GI,GR,GL,GD,GP,GU,GT,GN,GW,GY,HT,HM,HN,HK,HU,IS,IN,ID,IR,IQ,IE,IL,IT,CI,JM,JP,JO,KZ,KE,KI,KP,KR,KW,KG,LA,LV,LB,LS,LR,LY,LI,LT,LU,MO,MK,MG,MW,MY,MV,ML,MT,MH,MQ,MR,MU,TY,MX,FM,MD,MC,MN,MS,MA,MZ,MM,NA,NR,NP,NL,AN,NC,NZ,NI,NE,NG,NU,NF,MP,NO,OM,PK,PW,PA,PG,PY,PE,PH,PN,PL,PT,PR,QA,SS,RE,RO,RU,RW,KN,LC,VC,WS,SM,ST,SA,SN,RS,SC,SL,SG,SK,SI,SB,SO,ZA,GS,ES,LK,SH,PM,SD,SR,SJ,SZ,SE,CH,SY,TW,TJ,TZ,TH,TG,TK,TO,TT,TN,TR,TM,TC,TV,UG,UA,AE,GB,UM,UY,UZ,VU,VA,VE,VN,VG,VI,WF,EH,YE,YU,ZR,ZM,ZW,mobile|unique:users,phonenumber|min:6',
            'country_code'    =>  'required_with:phone|exists:countries,code'
        ]);
        $email = $request->email;
        $checkEmail = User::where('email',$email)->first();
        if(!empty($checkEmail))
        {
            return response()->json(['success'=>false,'message'=>'This is email already exist try other one']);
        }
        $number = (string) PhoneNumber::make($request->phone, $request->country_code); 
        $user = User::create([
            'name'  => $request->name,
            'first_name'  => $request->first_name,
            'last_name'  => $request->last_name,
            'email' =>  $email,
            'avatar'    => Storage::url('users/default.png'),
            'password'  =>  bcrypt($request->password),
            'currency_id'   =>   $currency->id,
            'whatsapp'  =>  $number,
            'phonenumber'   =>  $request->phone,
            'verification_token'  => str_random(40),
        ]);
      
        if ($user) {
            $newwallet = wallet::create([
                'is_crypto' =>  $currency->is_crypto,
                'user_id'   => $user->id,
                'amount'    =>  0,
                'currency_id'   => $currency->id,
                'accont_identifier_mechanism_value' => 'mimic adress',
                'transfer_method_id' => $transferMethod->id
            ]);
            $user->wallet_id = $newwallet->id;
            $newwallet->TransferMethods()->attach($transferMethod, ['user_id'=>$user->id,'adress' => 'mimic adress']);
            $saved = $user->save();
            if($saved) 
            {
                return response()->json(['success'=>true,'message'=>'User Registered Successfully']);
            }
            else
            {
                return response()->json(['success'=>false,'message'=>'Error Please try again!']);
            }
        }
    }
    public function exchange_currency($user_id=null ,request $request)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user)) 
        {
            $amount = $request->amount;
            $total_amount = $request->total_amount;
            if($amount <= 0)
            {
                return response()->json(['success'=>false,'message'=>'Invalid amount!']);
            }
            $from_currency_id = $request->from_currency_id;
            $to_currency_id = $request->to_currency_id;
            $from_currency = Currency::where(['id'=>$from_currency_id])->first();
            $userWallet = Wallet::where(['user_id'=>$user->id,'currency_id'=>$from_currency_id])->first();
            if(empty($userWallet))
            {
                return response()->json(['success'=>false,'message'=>'From currency does not existing!']);
            }
            $to_currency = Currency::where(['id'=>$to_currency_id])->first();
            $to_userWallet = Wallet::where(['user_id'=>$user->id,'currency_id'=>$to_currency_id])->first();
            if(empty($to_userWallet))
            {
                return response()->json(['success'=>false,'message'=>'To currency does not existing!']);
            }
            if($userWallet->fiat < $amount)
            {
                return response()->json(['success'=>false,'message'=>'Insufficient  Balance !!']);
            }
            $total_discount = 0;
            $amount = ($amount - $total_discount);
            $userWallet->fiat = $userWallet->fiat - $amount;
            $userWallet->save();
            $fee = 0;
            // $user = auth()->user();
            $trx = Money::getTrx();
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->request_id = $trx;
            $transaction->transactionable_id = $trx;
            $transaction->transactionable_type = '';
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
            $to_userWallet->fiat = '';
            $to_userWallet->save();
            $trx = Money::getTrx();
            $fee = 0;
            $to_transaction = new Transaction();
            $to_transaction->user_id = $user->id;
            $to_transaction->request_id = $trx;
            $to_transaction->transactionable_id = $trx;
            $to_transaction->transactionable_type = '';
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
            $currency_exchange->user_id =$user->id;
            $currency_exchange->from_currency_id = $from_currency_id;
            $currency_exchange->to_currency_id = $to_currency_id;
            $currency_exchange->amount = $amount;
            $currency_exchange->exchange_rate = '';
            $currency_exchange->total_amount = '';
            $saved = $currency_exchange->save();
            if($saved)
            {
                return response()->json(['success'=>true,'message'=>'saved successfully']);
            }
            else
            {
                return response()->json(['success'=>false,'message'=>'Error Please try again!']);
            }
        }
        else
        {
            return response()->json(['success'=>false,'message'=>'User Not exist']);
        }
    }
    public function virtual_card($user_id=null ,request $request)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user))
        {
            $currency_detail = Currency::where(['code'=>strtoupper($this->currency)])->first();
            $currency_id = isset($currency_detail->id) ? $currency_detail->id:'';
            $userWallet = Wallet::where(['user_id'=>$user->id,'currency_id'=>$currency_id])->first();
            $currency_id  = $userWallet->currency_id;
            $currency = Currency::find($currency_id);
            $amount = $request->amount;
            $fee_amount = ($amount/100) * $this->percentage;
            $total_in_amount = ($fee_amount + $this->fee);
            $minBalance = $userWallet->fiat - $total_in_amount;
            $this->validate($request,[
                'amount'=>'bail|required|gte:5|lte:'.$minBalance,
                'holder'=>'bail|required'
            ]);
            if($userWallet->fiat < ($amount + $total_in_amount))
            {
                return response()->json(['success'=>false,'message'=>'Insufficient wallet balance']);
            }
            $post_data = array(
                "public_key"=> $this->public_key,
                "card_type" => $this->cardtypemaster,
                "name_on_card" => $request->holder,
                "amount" => $amount,
            );
            /*** USE THIS IN PRODUCTION:****/
            $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://sandbox.strowallet.com/api/create-card',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $post_data
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $resp = json_decode($response, true);
            if(isset($resp['success']) && ($resp['success'] == false || $resp['success'] == 0))
            {
                return response()->json(['success'=>false,'message'=>$resp['message']]);
            }
            if(isset($resp['success']) && ($resp['success'] == true) || $resp['success'] == 1)
            {
                $userWallet->fiat = $userWallet->fiat - $total_in_amount - $amount;
                $userWallet->save();
                $updated_at = str_replace('T',' ',$resp['data']['updated_at']);
                $created_at = str_replace('T',' ',$resp['data']['created_at']);
                $trx = $resp['data']['trx'];
                $vcard = new Vcard();
                $vcard->rave_id=$resp['data']['card_id'];
                $vcard->user_id=Auth::user()->id;
                $vcard->cardpan=$resp['data']['card_number'];
                $vcard->maskedpan=$resp['data']['card_number'];
                $vcard->expiration=$resp['data']['expiry_month'].'/'.$resp['data']['expiry_year'];
                $vcard->type=$resp['data']['card_type'];
                $vcard->is_active=($resp['data']['status'] == 'active') ? 1:0;
                $vcard->created_at=date('Y-m-d H:i:s',strtotime($created_at));
                $vcard->updated_at=date('Y-m-d H:i:s',strtotime($updated_at));
                $vcard->trx=$trx;
                $vcard->save();
                
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->request_id = $trx;
                $transaction->transactionable_id = $vcard->rave_id;
                $transaction->transactionable_type = '';
                $transaction->entity_id = $vcard->rave_id;
                $transaction->entity_name = isset($user->name) ? $user->name:'';
                $transaction->transaction_state_id = 1;
                $transaction->money_flow = '-';
                $transaction->thumb = isset($user->avatar) ? $user->avatar:'';
                $transaction->currency_id = $currency->id;
                $transaction->currency = $currency->code;
                $transaction->currency_symbol = isset($currency->symbol) ? $currency->symbol:'';
                $transaction->activity_title = 'Vcard created successfully';
                $transaction->gross = $amount;
                $transaction->fee = $total_in_amount;
                $transaction->net = ($total_in_amount + $amount);
                $transaction->balance = $userWallet->fiat;
                $transaction->save();
                if($saved)
                {
                    return response()->json(['success'=>true,'message'=>$resp['message']]);
                }
                else
                {
                    return response()->json(['success'=>false,'message'=>'Error Please try again!']);
                }
            }
        }
        else
        {
                return response()->json(['success'=>false,'message'=>'User not Exist']);
        }
    }
    public function request_money($user_id=null,request $request)
    {
        $auth_user = User::where('id',$user_id)->first();
        if(!empty($auth_user))
        {
           
            if ($request->amount <= 0)
            {
                return response()->json(['success'=>false,'message'=>'Please insert an amount greater than 0']);
            }
            $checkEmail = User::where('email',$request->email)->first();
            if(!empty($checkEmail))
            {
                $this->validate($request, [
                    'amount'    =>  'required|numeric|min:2',
                    'description'   =>  'required|string',
                    'email' =>  'required|email|exists:users,email',
                ]);
            }
            else
            {
                $this->validate($request, [
                    'amount'    =>  'required|numeric|between:0,'.$auth_user->currentWallet()->amount,
                    'description'   =>  'required|string',
                ]);
                $valid_user = User::where('name', $request->email)->first();
                if (is_null($valid_user))
                {
                return response()->json(['success'=>false,'message'=>'The Username '. $request->email .__(' is invalid')]);
                }
            }
            if (filter_var($request->email, FILTER_VALIDATE_EMAIL))
            {
                $user = User::where('email', $request->email)->first();
            }
            else
            {
                $user = $valid_user ;
            }
            if($user->id == $auth_user->id )
            {
                    return response()->json(['success'=>false,'message'=>'Not allowed to send or receive funds from your own account']);
            }
            $currency = Currency::find($auth_user->currency_id);
            if ( $currency->is_crypto == 1 )
            {
                $precision = 8 ;
            }
            else
            {
                $precision = 2;
            }
            $auth_wallet = $user->walletByCurrencyId($currency->id);
            if($auth_wallet == NULL)
            {
                    return response()->json(['success'=>false,'message'=>'The user ' . $user->name." have not activated a wallet on his account yet !"]);
            }
            
            if((boolean)$currency == false )
            {
              return response()->json(['success'=>false,'message'=>'Wops, something went wrong... looks like we do not support this currency. please contact support if this error persists !']);
            }
            if ($auth_user->account_status == 0 )
            {
                return response()->json(['success'=>true,'message'=>'account is under a withdrawal request review proccess. please wait for a few minutes and try again']);
            }
            if($request->email == $auth_user->email)
            {
                return response()->json(['success'=>false,'message'=>'You can\'t request money to the same account you are in']);
            } 
            if ($request->amount > $auth_wallet->amount)
            {
                return response()->json(['success'=>false,'message'=>$user->name . __(' has insufficient funds to send').' <strong>'.$request->amount.__('to'). __('you') .'</strong>']);
            }
            
            $send_fee = 0; //free to send money
             
            if($currency->is_crypto == 1 )
            {
                $receive_fee = bcmul(''.( general_setting('mt_percentage_fee')/100) , $request->amount , $precision ) ;
            }
            else if ($currency->is_crypto == 0 )
            {
                $receive_fee = bcadd( bcmul(''.( general_setting('mt_percentage_fee')/100) , $request->amount , $precision ) , general_setting('mt_fixed_fee') , $precision ) ;
            }
            if ( ($request->amount - $receive_fee) < 0 )
            {
                return response()->json(['success'=>false,'message'=>('The minimum amount to send is').' <strong>'.bcsub ( $request->amount , $receive_fee , $precision ) .'</strong>']);
            }
            $receive = Receive::create([
                'user_id'   =>   $auth_user->id,
                'from_id'        => '',
                'transaction_state_id'  =>  3, // waiting confirmation
                'gross'    =>  $request->amount,
                'currency_id' =>  $currency->id,
                'currency_symbol' =>  $currency->symbol,
                'fee'   =>  $receive_fee,
                'net'   => bcsub( $request->amount , $receive_fee, $precision ),
                'description'   =>  $request->description,
            ]);
            $send = Send::create([
                'user_id'   =>  $user->id,
                'to_id'        =>  '',
                'transaction_state_id'  =>  3, // waiting confirmation 
                'gross'    =>  $request->amount,
                'currency_id' =>  $currency->id,
                'currency_symbol' =>  $currency->symbol,
                'fee'   =>  $send_fee,
                'net'   =>  bcsub ( $request->amount , $send_fee, $precision ),
                'description'   =>  $request->description,
                'receive_id'    =>  $receive->id
            ]);
            $receive->send_id = $send->id;
            $receive->save();
            $auth_user->RecentActivity()->save($receive->Transactions()->create([
                'user_id' => $receive->user_id,
                'entity_id'   =>  $receive->id,
                'entity_name' =>  $auth_user->name,
                'transaction_state_id'  =>  3, // waiting confirmation
                'money_flow'    => '+',
                'currency_id' =>  $currency->id,
                'thumb' =>  $auth_user->avatar,
                'currency_symbol' =>  $currency->symbol,
                'activity_title'    =>  'Money Received',
                'gross' =>  $receive->gross,
                'fee'   =>  $receive->fee,
                'net'   =>  $receive->net,
            ]));
            $user->RecentActivity()->save($send->Transactions()->create([
                'user_id' =>  $auth_user->id,
                'entity_id'   =>  $send->id,
                'entity_name' =>  $user->name,
                'transaction_state_id'  =>  3, // waiting confirmation
                'money_flow'    => '-',
                'thumb' =>  $user->avatar,
                'currency_id' =>  $currency->id,
                'currency_symbol' =>  $currency->symbol,
                'activity_title'    =>  'Money Sent',
                'gross' =>  $send->gross,
                'fee'   =>  $send->fee,
                'net'   =>  $send->net
            ]));
        }
        else
        {
            return response()->json(['success'=>false,'message'=>'User not Exist']);
            
        }
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
            'client_id'=>'9i6KpWSlPhE6GS1kaxaxWZLcJVPCOAAj',
            'client_secret'=>'h94yXg02wF0iKXbu_H1gz50b8RKAUmVj3JKzPBQyfYckN_wQESxOeoRq1KFrLm_h',
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
    public function get_country_currency($user_id=null)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user))
        {
            $countries = $this->getAirtimeCountries();
            return response()->json(['success'=>true,'message'=>$countries]);
        }
        else
        {
            return response()->json(['success'=>false,'message'=>'User not Exist']);
        }
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
    public function getOperatores($user_id=null,$country_code = null,$currency_code = null)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user))
        {
            if($country_code!=null)
            {
                $operators = $this->getCountriesOperators($country_code);
                return response()->json(['success'=>true,'message'=>$operators]);
            }
        }
        else
        {
                return response()->json(['success'=>false,'message'=>'User not exist']);
        }
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
    private function getCurrency($currency_code=null)
    {
        $data = DB::table('currencies')->where('code','USD')->first();
        return $data;
    }
    public function top_up_mobile($user_id=null , Request $request)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user))
        {
            $amount = $request->amount;
            $currency_data = $this->getCurrency();
            $currency_id = isset($currency_data->id) ? $currency_data->id:'';
            if (!$currency_id) 
            {
                return response()->json(['success'=>false,'message'=>'Your Currency Not Found!!']);
            }
            $userWallet = $user->walletByCurrencyId($currency_id);
            if (!$userWallet) 
            {
                return response()->json(['success'=>false,'message'=>'Your Wallet Not Found!!']);
            }
            if($userWallet->fiat < $amount)
            {
                return response()->json(['success'=>false,'message'=>'Insufficient  Balance !!']);
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
                $remaining_fiat = ($userWallet->fiat - $total_deduction);
                DB::table('wallets')->where(['currency_id'=>$currency_id,'user_id'=>$user_id])->update(['fiat'=>$remaining_fiat]);
                $this->postTransactionable($total_deduction,$transactionId);
                return response()->json(['success'=>true,'message'=>'Successfully Completed.']);
            }
            else
            {
                $message = isset($response->message) ? $response->message:'';
                return response()->json(['success'=>false,'message'=>$message]);
            }
            return $response;
        }
        else
        {
                return response()->json(['success'=>false,'message'=>'User not exist!!']);
        }
    }
    public function card_list($user_id=null)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user))
        {
            $vcards = Vcard::where(['user_id'=>$user_id])->latest()->paginate(15);
            return response()->json(['success'=>true,'data'=>$vcards]);
        }
        else
        {
            return response()->json(['success'=>false,'message'=>'User not exist!!']);
        }
    }
    public function card_details($card_id=null,$user_id=null , request $request)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user))
        {
            if(!isset($request['id']))
            {
                abort(404);   
            }
            $id = $request['id'];
            $vcard = Vcard::where(['user_id'=>$user_id,'id'=>$id])->first();
            if(!$vcard)
            {
                abort(404);   
            }
            /*******GETTING CARD CRITICAL INFO (CVV,BALANCE)*********/
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://sandbox.strowallet.com/api/card-details',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('public_key' => $this->public_key,'card_id'=>$vcard->rave_id),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $resp = json_decode($response, true);
            if($resp['success'] == false || $resp['success'] == 0)
            {
                return response()->json($resp['message']);
            }
            $updated_at = str_replace('T',' ',$resp['data']['updated_at']);
            $created_at = str_replace('T',' ',$resp['data']['created_at']);
            $vcard->cardpan=$resp['data']['card_number'];
            $vcard->maskedpan=$resp['data']['card_number'];
            $vcard->expiration=$resp['data']['expiry_month'].'/'.$resp['data']['expiry_year'];
            $vcard->type=$resp['data']['card_type'];
            $vcard->is_active=($resp['data']['status'] == 'active') ? 1:0;
            $vcard->created_at=date('Y-m-d H:i:s',strtotime($created_at));
            $vcard->updated_at=date('Y-m-d H:i:s',strtotime($updated_at));
            $vcard->save();
            $vcard = Vcard::where(['user_id'=>$user_id,'id'=>$vcard->id])->first();
            $data['data'] = $resp['data'];
            $data['vcard']=$vcard;
            /*******GETTING TRANSACTIONS*********/
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://sandbox.strowallet.com/api/card-history',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('public_key' =>$this->public_key,'card_id'=>$vcard->rave_id),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $resp = json_decode($response, true);
            if($resp['success'] == false || $resp['success'] == 0)
            {
                return response()->json(['success'=>false,'message'=>$resp['message']]);
            }
            $collection = collect($resp['data']);
            $page = request()->get('page');
            $perPage = 10;
            $tranxs = new LengthAwarePaginator(
                $collection->forPage($page, $perPage), $collection->count(), $perPage, $page
            );
            $tranxs->setPath('vcard/detail/'.$vcard->id);
            $data['tranxs'] = $tranxs;
                return response()->json(['success'=>true,'message'=>$data['tranxs']]);
        }
        else
        {
                return response()->json(['success'=>false,'message'=>'User not exist']);
        }
    }
    public function card_fund($user_id=null,request $request)
    {
        $login_user = User::where('id',$user_id)->first();
        if(!empty($user))
        {
            if(!isset($request['id']))
            {
                abort(404);   
            }
            $id = $request['id'];
            $vcard = Vcard::where(['user_id'=>$user_id,'id'=>$id])->first();
            if(!$vcard){
                abort(404);   
            }
            
            $user = User::find($user_id);
            $currency = Currency::where(['code'=>strtoupper($this->currency)])->first();
            $currency_id = isset($currency->id) ? $currency->id:'';
            $userWallet = Wallet::where(['user_id'=>$login_user->id,'currency_id'=>$currency_id])->first();
            $this->validate($request,[
                'amount'=>'bail|required|gt:0|lte:'.$userWallet->fiat
            ]);
            $amount = $request['amount'];
            $fee_amount = ($amount/100) * $this->fund_fee_percentage;
            $total_fee= ($fee_amount + $this->fund_fee);
            if($userWallet->fiat < ($amount + $total_fee))
            {
                return response()->json(['success'=>false,'message'=>'Insufficient wallet balance.']);
            }
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://sandbox.strowallet.com/api/fund-card',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('public_key' => $this->public_key,'amount'=>$amount,'card_id' => $vcard->rave_id),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $resp = json_decode($response, true);
            if($resp['success'] == false || $resp['success'] == 0)
            {
                return response()->json(['success'=>false,'message'=>$resp['message']]);
            }
            if($resp['success'] == true || $resp['success'] == 1)
            {
                $userWallet->fiat-=($amount + $total_fee);
                $userWallet->save();
                
                $trx = Money::getTrx();
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->request_id = $trx;
                $transaction->transactionable_id = $trx;
                $transaction->transactionable_type = 'App\Models\Vcard';
                $transaction->entity_id = $trx;
                $transaction->entity_name = isset($user->name) ? $user->name:'';
                $transaction->transaction_state_id = 1;
                $transaction->money_flow = '-';
                $transaction->thumb = isset($user->avatar) ? $user->avatar:'';
                $transaction->currency_id = $currency->id;
                $transaction->currency = $currency->code;
                $transaction->currency_symbol = isset($currency->symbol) ? $currency->symbol:'';
                $transaction->activity_title = 'Vcard funded';
                $transaction->gross = $amount;
                $transaction->fee = $total_fee;
                $transaction->net = ($total_fee + $amount);
                $transaction->balance = $userWallet->fiat;
                $saved = $transaction->save();
                if($saved)
                {
                    return response()->json(['success'=>true,'message'=>'Done Successfully']);
                }
                else
                {
                    return response()->json(['success'=>false,'message'=>'Error try again']);
                }
            }
        }
        else
        {
                return response()->json(['success'=>false,'message'=>'User not exist']);
        }
    }
    public function login(request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=>'required',
            'password'=>'required'
        ]);
        $email = $request->email;
        $password = bcrypt($request->password);
        $user = User::where('email',$email)->where('password',$password)->first();
        if(!empty($user))
        {
            return response()->json(['success'=>true,'message'=>'you are Login Successfully']);
        }
        else
        {
            return response()->json(['success'=>false,'message'=>'Either Email or password is incorrect']);
        }
    }
    public function order_gift_card($user_id=null , Request $request)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user))
        {
            $wallet = $user->currentWallet();
            $minBalance = $wallet->amount;
            $this->validate($request,[
                'country_id'=>'required',
                'card_id'=>'required',
                'price'=>'required',
                'qty'=>'required',
            ]);
            $price = $request->price;
            $qty = 1;
            if($qty <= 0)
            {
            return response()->json(['success'=>false,'message'=>'Quantity should be greater 0']);
            }
            $card_id = $request->card_id;
            $country_id = $request->country_id;
            $country_detail = Country::find($country_id);
            $country_code = isset($country_detail->code) ? $country_detail->code:'';
            $fee = $this->gift_card_fee_percentage;
            $fee_amount = ($price/100) * $fee;
            $total_amount = ($price + $fee_amount);
            if($total_amount < 0)
            {
                return response()->json(['success'=>false,'message'=>'Amount should be greater 0']);
            }
            if($total_amount > $minBalance)
            {
                return response()->json(['success'=>false,'message'=>'You have low balance']);
            }
            $email = $user->email;
            $username = $user->name;
            $name = $user->name;
            $post_data = [];
            $post_data['productId'] = $card_id;
            $post_data['countryCode'] = $country_code;
            $post_data['quantity'] = $qty;
            $post_data['unitPrice'] = $price;
            $post_data['customIdentifier'] = $request->customer_name;
            $post_data['senderName'] = $name;
            $post_data['recipientEmail'] = $this->admin_email;
            $post_data = json_encode($post_data);
            $accessToken = $this->getAccessToken();
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => GIFT_CARD_API_URL.'/orders',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>$post_data,
                  CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.$accessToken,
                    'Content-Type: application/json',
                    'Accept: application/com.reloadly.giftcards-v1+json'
                  ),
                ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response);
            if(isset($response->status) && $response->status == 'SUCCESSFUL')
            {
                $getRedeemCode = $this->getRedeemCode($response->transactionId);
                $cardNumber = isset($getRedeemCode[0]->cardNumber) ? $getRedeemCode[0]->cardNumber:'';
                $pinCode = isset($getRedeemCode[0]->pinCode) ? $getRedeemCode[0]->pinCode:'';
                $wallet->amount = ($wallet->amount - $total_amount);
                $wallet->save();
                    Transaction::create([
                    'user_id' =>  $user->id,
                    'entity_id'   =>  $card_id,
                    'transactionable_id'=>isset($response->transactionId) ? $response->transactionId:'',
                    'entity_name' =>  $wallet->currency->name,
                    'transaction_state_id'  =>  1,
                    'money_flow'    => '-',
                    'activity_title'    =>  'Gift Card',
                    'currency_id' =>  $wallet->currency->id,
                    'currency_symbol' =>  $wallet->currency->symbol,
                    'thumb' =>  $wallet->currency->thumb,
                    'gross' =>  $price,
                    'fee'   =>  $fee_amount,
                    'net'   =>  $total_amount,
                    'balance'   =>  $wallet->amount,
                ]);
                $message = 'Card purchased successfully';
                $message.='<br>';
                $message.='cardNumber is: '.$cardNumber;
                $message.='<br>';
                $message.='pinCode is: '.$pinCode;
                Mail::send(new GiftCardEmail($user->email,$cardNumber,$pinCode));
                return response()->json(['success'=>true,'message'=>$message]);
            }
            else
            {
                $message = isset($response->message) ? $response->message:'';
                return response()->json($message);
            }
        }
        else
        {
            return response()->json(['success'=>false,'message'=>'User not exist']);
        }
    }
    public function get_gift_cards($user_id = null,$code = null,$country_id = null)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user))
        {
            $giftcards = '';
            if($code!=null)
            {
                $giftcards = $this->getGiftCardsAccordingToCode($code);
                return response()->json(['success'=>true,'date'=>$giftcards]);
            }
        }
        else
        {
            return response()->json(['success'=>false,'message'=>'User not exist']);
        }
    }
    public function gift_cards_price($user_id = null,$code = null,$country_id = null,$product_id = null)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user))
        {
            $price_detail='';
            if($product_id!=null)
            {
                $product_detail = $this->getGiftCardsById($product_id);
                $price_detail = isset($product_detail->fixedRecipientDenominations) ? $product_detail->fixedRecipientDenominations:'';
                return response()->json($price_detail);
                if(empty($price_detail))
                {
                    return response()->json(['success'=>false,'message'=>'This Gift Card not available at this moment']);
                }
            }
        }
        else
        {
            return response()->json(['success'=>false,'message'=>'User not exist']);
        }
    }
    private function getGiftCardsAccordingToCode($code = null)
    {
        $data = [];
        if($code!=null)
        {
            $accessToken = $this->getAccessToken();
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => GIFT_CARD_API_URL.'/countries/es/products',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$accessToken,
                'Accept: application/com.reloadly.giftcards-v1+json'
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
           $data = json_decode($response);
        }
        return $data;
    }
    private function getAccessToken()
    {
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
          CURLOPT_POSTFIELDS =>'{
            "client_id":"9i6KpWSlPhE6GS1kaxaxWZLcJVPCOAAj",
            "client_secret":"h94yXg02wF0iKXbu_H1gz50b8RKAUmVj3JKzPBQyfYckN_wQESxOeoRq1KFrLm_h",
            "grant_type":"client_credentials",
            "audience":"'.GIFT_CARD_API_URL.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response);
        $accessToken = isset($result->access_token) ? $result->access_token:'';
        return $accessToken;
    }
    private function getGiftCardsById($product_id = null)
    {
        $data = [];
        if($product_id!=null)
        {
            $accessToken = $this->getAccessToken();
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => GIFT_CARD_API_URL.'/products/'.$product_id,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$accessToken,
                'Accept: application/com.reloadly.giftcards-v1+json'
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
           $data = json_decode($response);
        }
        return $data;
    }
    public function get_transaction($user_id=null)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user))
        {
            $transaction = Transaction::all();
            return response()->json(['success'=>true,'message'=>$transaction]);
        }
        else
        {
            return response()->json(['success'=>false,'message'=>'User not exist']);
        }
    }
    public function get_wallet($user_id=null)
    {
        $user = User::where('id',$user_id)->first();
        if(!empty($user))
        {
            $transaction = Wallet::all();
            return response()->json(['success'=>true,'message'=>$transaction]);
        }
        else
        {
            return response()->json(['success'=>false,'message'=>'User not exist']);
        }
    }
}