<?php
namespace App\Http\Controllers;
use Auth;
use Mail;
use App\Models\Transaction;
use App\Models\Currency;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\Vcard;
use App\Http\Resources\VcardTransactionCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\User;
use App\Helpers\Money;
class VCardController extends Controller{
    public function __construct()
	{
		$this->city = "San Francisco";
        $this->state = "CA - California";
        $this->bp = "94105";
        $this->street1 = "333 Fremont Street";
        $this->street2 = "San Francisco City";
        $this->country = "US";
        $this->public_key = "VN21ZNMSYCOOJK56TA1KHCRE2ZTMWG";
        $this->fee = 1;
        $this->percentage = 1;
        $this->fund_fee = 1;
        $this->fund_fee_percentage = 1;
        $this->currency = "usd";
        $this->cardtypemaster = "master";
	}
    public function index()
    {
        $data['vcards'] = Vcard::where(['user_id'=>auth()->user()->id])->latest()->paginate(15);
        $data['page_title'] = "Virtual Card";
        return view('vcards.index', $data);
    }
    
    public function create(Request $request)
    {
        $data['fee'] = $this->fee;
        return view('vcards.create', $data);
    }
    public function details(Request $request)
    {
        if(!isset($request['id']))
        {
            abort(404);   
        }
        $id = $request['id'];
        $vcard = Vcard::where(['user_id'=>auth()->user()->id,'id'=>$id])->first();
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
            flash(__($resp['message']), 'danger');
            return back();
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
        $vcard = Vcard::where(['user_id'=>auth()->user()->id,'id'=>$vcard->id])->first();
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
            flash(__($resp['message']), 'danger');
            return back();
        }
        $collection = collect($resp['data']);
        $page = request()->get('page');
        $perPage = 10;
        $tranxs = new LengthAwarePaginator(
            $collection->forPage($page, $perPage), $collection->count(), $perPage, $page
        );
        $tranxs->setPath('vcard/detail/'.$vcard->id);
        $data['tranxs'] = $tranxs;
        return view('vcards.detail', $data);
    }
    public function store(Request $request)
    {
    	$currency_detail = Currency::where(['code'=>strtoupper($this->currency)])->first();
        $currency_id = isset($currency_detail->id) ? $currency_detail->id:'';
 		$userWallet = Wallet::where(['user_id'=>auth()->user()->id,'currency_id'=>$currency_id])->first();

        $currency_id  = $userWallet->currency_id;
        $currency = Currency::find($currency_id);
        $user = User::find(auth()->user()->id);
        $amount = $request['amount'];
        $fee_amount = ($amount/100) * $this->percentage;
        $total_in_amount = ($fee_amount + $this->fee);
        $minBalance = $userWallet->fiat - $total_in_amount;
        $this->validate($request,[
            'amount'=>'bail|required|gte:5|lte:'.$minBalance,
            'holder'=>'bail|required'
        ]);
        if($userWallet->fiat < ($amount + $total_in_amount))
        {
            flash(__('Insufficient wallet balance'), 'danger');
            return back();
        }
        $post_data = array(
            "public_key"=> $this->public_key,
            "customer_id"=> $user->customer_id,
	        "card_type" => $this->cardtypemaster,
	        "name_on_card" => $request['holder'],
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
            flash(__($resp['message']), 'danger');
            return back();
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
            $transaction->transactionable_type = 'App\Models\Vcard';
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
            flash(__($resp['message']), 'info');
            return back();
        }
    }
    public function fund(Request $request)
    {
        if(!isset($request['id']))
        {
            abort(404);   
        }
        $id = $request['id'];
        $vcard = Vcard::where(['user_id'=>auth()->user()->id,'id'=>$id])->first();
        if(!$vcard)
        {
            abort(404);   
        }
        $data['vcard'] = $vcard;
        $data['id'] = $id;
        return view('vcards.fund', $data);
    }
    
    
    /*
     * Ask user confirmation before deleting a card
     *
    */
    public function delete(Request $request)
    {
        if(!isset($request['id']))
        {
            abort(404);   
        }   
        $id = $request['id'];
        $vcard = Auth::user()->vcards()->where('id',$id)->first();
        if(!$vcard)
        {
            abort(404);   
        }
        $notify[] = ['success','Deleted successfully'];
        return back()->withNotify($notify);
    }
    
    
    
    /*
    * Effectively delete the selected card
    */
    public function confirmDelete(Request $request){
        
        if(!isset($request['id'])){
            abort(404);   
        }
        
        $id = $request['id'];
        $vcard = Auth::user()->vcards()->where('id',$id)->first();
        
        if(!$vcard){
            abort(404);   
        }
        
        $query = array(
            "secret_key" => $this->SECRET_KEY
            );
        $data_string = json_encode($query);
        $ch = curl_init('https://api.ravepay.co/v2/services/virtualcards/'.$vcard->rave_id.'/terminate');                                                    
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                              
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        
        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        curl_close($ch);
        
        $resp = json_decode($response, true);
        if(isset($resp['Status']) && $resp['Status'] == "success"){
            Vcard::where('id',$id)->delete();
            return redirect('/vcards');
        }else{
            return redirect()->route('vcard.error')->with([
                'response'=>$response
                ]);
        }
        
    }
    public function postFund(Request $request)
    {
        if(!isset($request['id']))
        {
            abort(404);   
        }
        
        $id = $request['id'];
        $vcard = Vcard::where(['user_id'=>auth()->user()->id,'id'=>$id])->first();
        if(!$vcard){
            abort(404);   
        }
        
        $user = User::find(auth()->user()->id);
        $currency = Currency::where(['code'=>strtoupper($this->currency)])->first();
        $currency_id = isset($currency->id) ? $currency->id:'';
 		$userWallet = Wallet::where(['user_id'=>auth()->user()->id,'currency_id'=>$currency_id])->first();
        $this->validate($request,[
            'amount'=>'bail|required|gt:0|lte:'.$userWallet->fiat
        ]);
        $amount = $request['amount'];
        $fee_amount = ($amount/100) * $this->fund_fee_percentage;
        $total_fee= ($fee_amount + $this->fund_fee);
        if($userWallet->fiat < ($amount + $total_fee))
        {
            flash(__('Insufficient wallet balance.'), 'danger');
            return back();
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
            flash(__($resp['message']), 'danger');
            return back();
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
            $transaction->save();
            flash(__($resp['message']), 'info');
            return back();
        }
    }
    
    
    
    
    /*CARD CREATED: Array ( [status] => success [message] => Please note that this card can ONLY be used on RAVE merchant sites. Contact support for more information [data] => Array ( [id] => cb95e534-5810-4599-8b90-d0874450954f [AccountId] => 404298 [amount] => 100.00 [currency] => NGN [card_hash] => cb95e534-5810-4599-8b90-d0874450954f [cardpan] => 4685889611957887 [maskedpan] => 468588*******7887 [city] => Lekki [state] => Lagos [address_1] => 19, Olubunmi Rotimi [address_2] => [zip_code] => 23401 [cvv] => 430 [expiration] => 2023-05 [send_to] => [bin_check_name] => [card_type] => visa [name_on_card] => Payzoft [date_created] => 2020-05-25T18:07:25.7321572+00:00 [is_active] => 1 [callback_url] => [is_virtual] => ) ) */
}