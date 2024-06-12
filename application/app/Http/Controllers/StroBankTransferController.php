<?php
namespace App\Http\Controllers;
use App\User;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Transaction;
use Session;
use Image;
use File;
use Validator;
use App\Models\Currency;
use App\Models\Wallet;
use App\Helpers\Money;
class StroBankTransferController extends Controller
{
    public function __construct()
    {
        $this->currency = "ngn";
        $this->fee_amount = 12;
        $this->fee_in_percentage = 0;
    }
    public function index()
    {
        $data['page_title'] = "Stro Bank Transfer";
        $banksList = Money::get_stro_bank_list();
        $data['banksList'] = isset($banksList->data->bank_list) ? $banksList->data->bank_list:'';
        return view('stro_bank_transfer.index', $data);
    }
    public function stroBankPostRequest(Request $request)
    {
    	$this->validate($request, [
            'amount'   =>  'required',
            'bank_name'   =>  'required',
            'account_number'   =>  'required',
        ]);
    	$amount = isset($request->amount) ? $request->amount:'';
        if($amount<=0)
        {
            flash(__('Invalid amount') , 'danger');
            return  back();
        }
        $fee_amount = $this->fee_amount;
        $fee_percentage_amount = (($amount/100) * $this->fee_in_percentage);
        $total_fee = ($fee_amount + $fee_percentage_amount);
        $user = User::find(auth()->user()->id);

        $currency = Currency::where(['code'=>strtoupper($this->currency)])->first();
        $currency_id = isset($currency->id) ? $currency->id:'';
        $userWallet = Wallet::where(['user_id'=>auth()->user()->id,'currency_id'=>$currency_id])->first();
        $currency_id  = $userWallet->currency_id;
        if($userWallet->fiat < $amount)
        {
            flash(__('Insufficient  Balance !!') , 'danger');
            return  back();
        }
        $narration = isset($request->narration) ? $request->narration:'';
        $bank_code = isset($request->bank_code) ? $request->bank_code:'';
        $bank_name = isset($request->bank_name) ? $request->bank_name:'';
        $account_number = isset($request->account_number) ? $request->account_number:'';
        $result = Money::get_stro_account_name($bank_code,$account_number);
        // echo '<pre>',print_r($result),'</pre>';exit();
        $statusCode = isset($result->success) ? $result->success:'';
        if($statusCode == '1' || $statusCode == true)
        {
            $response_data = isset($result->data) ? $result->data:'';
            $account_name = isset($response_data->account_name) ? $response_data->account_name:'';
            $sessionId = isset($response_data->sessionId) ? $response_data->sessionId:'';
            $posted_data = [];
            $posted_data['amount'] = $amount;
            $posted_data['fee'] = $total_fee;
            $posted_data['narration'] = $narration;
            $posted_data['bank_code'] = $bank_code;
            $posted_data['bank_name'] = $bank_name;
            $posted_data['account_number'] = $account_number;
            $posted_data['account_name'] = $account_name;
            $posted_data['name_enquiry_reference'] = $sessionId;
            session()->put('transfer_posted_data',$posted_data);
            return redirect(route('stroPreview',app()->getLocale()));
        }
        else
        {
            flash(__('Invalid account number.Customer not found') , 'danger');
            return  back();
        }
    }
    public function stroPreview()
    {
        if(session()->has('transfer_posted_data'))
        {
            $post_data = session()->get('transfer_posted_data');
            $data['post_data'] = (object)$post_data;
        }
        $data['page_title'] = "Stro bank transfer";
        return view('stro_bank_transfer.preview', $data);
    }
    public function stroBankTransfer()
    {
        if(session()->has('transfer_posted_data'))
        {
	        $user = User::find(auth()->user()->id);
            $post_data = session()->get('transfer_posted_data');
            $post_data = (object)$post_data;

            $name_enquiry_reference = isset($post_data->name_enquiry_reference) ? $post_data->name_enquiry_reference:'';
            $bank_name = isset($post_data->bank_name) ? $post_data->bank_name:'';
            $bank_code = isset($post_data->bank_code) ? $post_data->bank_code:'';
            $account_number = isset($post_data->account_number) ? $post_data->account_number:'';
            $account_name = isset($post_data->account_name) ? $post_data->account_name:'';
            $amount = isset($post_data->amount) ? $post_data->amount:'';
            if($amount<=0)
            {
                flash(__('Invalid amount') , 'danger');
                return  back();
            }
            $total_fee = isset($post_data->fee) ? $post_data->fee:0;
            $narration = isset($post_data->narration) ? $post_data->narration:'';
            $currency = Currency::where(['code'=>strtoupper($this->currency)])->first();
            $currency_id = isset($currency->id) ? $currency->id:'';
            $userWallet = Wallet::where(['user_id'=>auth()->user()->id,'currency_id'=>$currency_id])->first();
            $currency_id  = $userWallet->currency_id;
            if($userWallet->fiat < ($amount + $total_fee))
            {
                flash(__('Insufficient  Balance !!') , 'danger');
                return  back();
            }
	        $public_key = STRO_PUBLIC_KEY;
            $bank_transfer = [];
            $bank_transfer['public_key'] = $public_key;
            $bank_transfer['amount'] = $amount;
            $bank_transfer['bank_name'] = $bank_name;
            $bank_transfer['bank_code'] = $bank_code;
            $bank_transfer['account_number'] = $account_number;
            $bank_transfer['account_name'] = $account_name;
            $bank_transfer['narration'] = $narration;
            if(isset($name_enquiry_reference) && $name_enquiry_reference)
            {
            	$bank_transfer['name_enquiry_reference'] = $name_enquiry_reference;
            }
            $total_amount = ($amount + $total_fee);
            $result = Money::get_stro_bank_transfer($bank_transfer);
            // echo '<pre>',print_r($result),'</pre>';exit();
	        $statusCode = isset($result->success) ? $result->success:'';
	        $message = isset($result->message) ? $result->message:'';
	        if($statusCode == '1' || $statusCode == true)
	        {
                $userWallet->fiat = $userWallet->fiat - $total_amount;
                $userWallet->save();
                $user = auth()->user();
                $trx = Money::getTrx();
                $fee = 0;
                $transaction = new Transaction();
                $transaction->user_id = auth()->user()->id;
                $transaction->request_id = $trx;
                $transaction->transactionable_id = $trx;
                $transaction->transactionable_type = 'transfer';
                $transaction->entity_id = $user->id;
                $transaction->entity_name = 'Bank transfer';
                $transaction->transaction_state_id = 1;
                $transaction->money_flow = '-';
                $transaction->thumb = isset($user->avatar) ? $user->avatar:'';
                $transaction->currency_id = $currency->id;
                $transaction->currency = $currency->code;
                $transaction->currency_symbol = isset($currency->symbol) ? $currency->symbol:'';
                $transaction->activity_title = 'Bank transfer';
                $transaction->gross = $amount;
                $transaction->fee = $fee;
                $transaction->net = $total_amount;
                $transaction->balance = $userWallet->fiat;
                $transaction->save();

                flash(__($message) , 'success');
                return  back();
	        }
	        else
	        {
                flash(__($message) , 'danger');
                return  back();
	        }
	    }
    }
}