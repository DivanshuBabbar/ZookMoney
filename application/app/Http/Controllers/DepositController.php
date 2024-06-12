<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\User;
use Mail;
use App\Models\Deposit;
use App\Models\DepositMethod;
use App\Models\TransferMethod;
use App\Mail\Deposit\depositCompletedUserNotificationEmail;
use App\Models\Wallet;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\Payout;
use Illuminate\Http\Request;
use App\Helpers\Money;
use Illuminate\Support\Facades\Validator;
use \App\Models\TransactionState;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use App\Jobs\SendUserDepositMail;
use Log;
use \App\Models\WhiteListAccount;
use \App\Models\BankAccount;

class DepositController extends Controller
{
    
    public function myDeposits(Request $request, $lang)
    {
        if(Auth::user()->currentWallet() == null)
        {
            return redirect(route('show.currencies', app()->getLocale()));
        }
        
        $id = Auth::user()->id;

        if (!request()->ajax()) {
            $active = 'Deposits';
            $page_title = 'All Deposits';
            $transactionStatus = TransactionState::query()->pluck('name', 'id');

            return view('deposits.index', compact('active', 'page_title', 'transactionStatus'));
        }
        
        $a = ['Manual Deposit From Dashboard','Manual Deposit'];
        $data = Transaction::query()->with('User','Status', 'Currencie','Deposits','Requests')->where('user_id', '=' , $id)->whereIn('activity_title',$a)
            ->when(!empty(request()->transaction_state_id), function ($query) {
                return $query->where('transaction_state_id', request()->transaction_state_id);
            })
            ->when(!empty(request()->activity_type), function ($query) {
                if (request()->activity_type == 'UPI') {
                    return $query->where('activity_title', 'Manual Deposit');
                }elseif (request()->activity_type == 'Wallet Deposit') {
                   return $query->where('activity_title', 'Manual Deposit From Dashboard');
                }else{
                    return $query->where('activity_title', request()->activity_type);
                }
            })
            ->when(!empty(request()->created_at_from), function ($query) {
                return $query->whereDate('created_at', '>=' ,date('Y-m-d', strtotime(request()->created_at_from)));
            })
            ->when(!empty(request()->created_at_to), function ($query) {
                return $query->whereDate('created_at', '<=' ,date('Y-m-d', strtotime(request()->created_at_to)));
            });
       

        if (request()->multiple_email) {
           $emails = request()->multiple_email;
        }

        if (request()->openEmailModal == '1') {
            $for_data = $data->get();
            $new_data = [];
            foreach ($for_data as $key => $value) {
                $new_data[] = $value;
            }
            $new_data['emails'] = $emails;
            try{
                dispatch(new SendUserDepositMail($new_data));
                Log::debug('Job dispatched successfully.');
            }catch (Exception $e) {
                Log::error('Error dispatching job: ' . $e->getMessage());
            }
        }else{
            $data = $data->latest();
        }
       
        return DataTables::eloquent($data)
            ->addColumn('user_name', function ($data) {
                return $data->User->name;
            }) 
            ->addColumn('status', function ($data) {
                $badge = '';
                if(!empty($data->Status)){
                    if($data->Status->name === 'Completed') {
                        $color = 'info';
                    } elseif ($data->Status->name === 'Canceled') {
                        $color = 'danger';
                    } else {
                        $color = 'warning';
                    }
                    $badge .= '<span class="badge badge-'.$color.'" >'.$data->Status->name.'</span>';

                }
                return $badge;
            }) 
            ->editColumn('transaction_receipt', function ($data) {
                $html = '';
                 if(!empty($data->Deposits) && !empty($data->Deposits->transaction_receipt)){
                    $url = url("assets/images/", $data->Deposits->transaction_receipt);
                    $html = '<a href="'.$url.'" target="blank" >
                                <img src="'.$url.'" alt="image" class="avatar" style="width: 50px; height: 50px;">
                            </a>';
                }

                return $html;
            })
            ->editColumn('activity_title', function ($data) {
                if ($data->activity_title == 'Manual Deposit') {
                    $data->activity_title = 'Upi';
                }elseif ($data->activity_title == 'Manual Deposit From Dashboard') {
                   $data->activity_title = 'Wallet Deposit';
                }else {
                    $data->activity_title = $data->activity_title;
                }
                return $data->activity_title;
            })
            
            ->editColumn('currency', function ($data) {
                return $data->Currencie->code;
            })

            ->addColumn('unique_transaction_id', function ($data) {
                if($data && $data->Deposits && $data->Deposits->unique_transaction_id){
                    return $data->Deposits->unique_transaction_id;
                }else{
                    return 'N/A';
                }
            })
            ->addColumn('ag_bank_reference_no', function ($data) {
                if($data && $data->Deposits && $data->Deposits->ag_bank_reference_no && $data->activity_title == 'Manual Deposit'){
                    return $data->Deposits->ag_bank_reference_no;
                }else{
                    return 'N/A';
                }
            })
            ->addColumn('ref', function ($data) {
                if($data && $data->Requests && $data->Requests->ref){
                    return $data->Requests->ref;
                }else{
                    return 'N/A';
                }
            })
            ->editColumn('created_at', function ($data) {
                return !empty($data->created_at) && ($data->created_at instanceof Carbon)? $data->created_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-';

            })
            ->addIndexColumn()
            ->rawColumns(['status','unique_transaction_id','ag_bank_reference_no','ref','transaction_receipt'])
            ->toJson();
    }

    public function myPayouts(Request $request, $lang){
        if(Auth::user()->currentWallet() == null)
        {
            return redirect(route('show.currencies', app()->getLocale()));
        }
        
        $id = Auth::user()->id;

        if (!request()->ajax()) {
            $active = 'Deposits';
            $page_title = 'All Payout Deposits';
            $transactionStatus = TransactionState::query()->pluck('name', 'id');

            return view('deposits.payout_deposits', compact('active', 'page_title', 'transactionStatus'));
        }
        
          
        $data = Payout::query()->with('User')->where('user_id', '=' , $id)->where('transactionable_type','Payout-Deposit')->orderBy('payout.id','desc')
             ->when(!empty(request()->transaction_state_id), function ($query) {
                return $query->where('transaction_state_id', request()->transaction_state_id);
            })
            ->when(!empty(request()->activity_type), function ($query) {
                return $query->where('transactionable_type', request()->activity_type);
            })
            ->when(!empty(request()->created_at_from), function ($query) {
                return $query->whereDate('created_at', '>=' ,date('Y-m-d', strtotime(request()->created_at_from)));
            })
            ->when(!empty(request()->created_at_to), function ($query) {
                return $query->whereDate('created_at', '<=' ,date('Y-m-d', strtotime(request()->created_at_to)));
            });
       

        if (request()->multiple_email) {
           $emails = request()->multiple_email;
        }

        if (request()->openEmailModal == '1') {
            $for_data = $data->get();
            $new_data = [];
            foreach ($for_data as $key => $value) {
                $new_data[] = $value;
            }
            $new_data['emails'] = $emails;
            try{
                dispatch(new SendUserDepositMail($new_data));
                Log::debug('Job dispatched successfully.');
            }catch (Exception $e) {
                Log::error('Error dispatching job: ' . $e->getMessage());
            }
        }else{
            $data = $data->latest();
        }
       
        return DataTables::eloquent($data)
            ->addColumn('user_name', function ($data) {
                return $data->User->name;
            }) 
            ->addColumn('status', function ($data) {
                $badge = '';
                if(!empty($data->transaction_state_id)){
                    if($data->transaction_state_id == 1) {
                        $color = 'info';
                        $name = 'Completed';
                    } elseif ($data->transaction_state_id == 2) {
                        $color = 'danger';
                        $name = 'Canceled';
                    } elseif ($data->transaction_state_id == 4) {
                        $color = 'info';
                        $name = 'Partially Completed';
                    }else {
                        $color = 'warning';
                        $name = 'Pending';
                    }
                    $badge .= '<span class="badge badge-'.$color.'" >'.$name.'</span>';

                }
                return $badge;
            }) 

            ->addColumn('unique_transaction_id', function ($data) {
                if($data && $data->Deposits && $data->Deposits->unique_transaction_id){
                    return $data->Deposits->unique_transaction_id;
                }else{
                    return 'N/A';
                }
            })
            ->addColumn('ag_bank_reference_no', function ($data) {
                if($data && $data->Deposits && $data->Deposits->ag_bank_reference_no && $data->activity_title == 'Manual Deposit'){
                    return $data->Deposits->ag_bank_reference_no;
                }else{
                    return 'N/A';
                }
            })
            ->addColumn('ref', function ($data) {
                if($data && $data->Requests && $data->Requests->ref){
                    return $data->Requests->ref;
                }else{
                    return 'N/A';
                }
            })
            ->editColumn('created_at', function ($data) {
                return !empty($data->created_at) && ($data->created_at instanceof Carbon)? $data->created_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-';

            })
            ->addIndexColumn()
            ->rawColumns(['status','unique_transaction_id','ag_bank_reference_no','ref'])
            ->toJson();
    }
    public function confirmDeposit(Request $request, $lang)
    {
    	if (Auth::user()->isAdministrator() == false ) 
        {
    		abort (404);
    	}
    	$deposit = Deposit::with('transferMethod')->findOrFail($request->id);
        $transferMethod = TransferMethod::findOrFail($deposit->transfer_method_id);
    	if($deposit->transaction_state_id == 1) 
        {
    		return redirect(url('/').'/admin/dashboard/deposits/'.$deposit->id);
    	}
    	$user = User::findOrFail($request->user_id);
        $wallet = Wallet::findOrFail($deposit->wallet_id);
        $deposit_fee = 0;
        if($wallet->is_crypto == 1)
        {
            $precision = 8 ;
        } 
        else 
        {
            $precision = 2;
        }
        $deposit_fee = bcadd( bcmul ( ( $transferMethod->deposit_percentage_fee / 100 ), $request->gross, $precision) , $transferMethod->deposit_fixed_fee, $precision ) ;
    
        $deposit_net = bcsub($request->gross, $deposit_fee, $precision );
        $wallet->amount = bcadd(''.$user->balance, ''.$deposit_net, $precision);
    	$user->RecentActivity()->save($deposit->Transactions()->create([
            'user_id' => $user->id,
            'entity_id'   =>  $user->id,
            'entity_name' =>   $transferMethod->name,
            'transaction_state_id'  =>  1, // completed
            'money_flow'    => '+',
            'activity_title'    =>  'Deposit',
            'balance'	=>	 $wallet->amount,
            'currency_id'   =>  $deposit->currency_id,
            'currency_symbol'   =>  $deposit->currency_symbol,
            'thumb' =>  $transferMethod->thumbnail,
            'gross' =>  $request->gross,
            'fee'   =>  $deposit_fee,
            'net'   =>  $deposit_net
        ]));
        
    	$deposit->gross = $request->gross;
    	$deposit->fee = $deposit_fee;
    	$deposit->net = $deposit_net;
    	$deposit->transaction_state_id = 1;
    	$deposit->save();
    	$wallet->save();
        Mail::send(new depositCompletedUserNotificationEmail( $deposit, $user));
    	
    	return redirect(url('/').'/admin/dashboard/deposits/'.$deposit->id);
    }
    public function add(Request $request)
    {
        $type = $request->input('type');
        $data['depositMethod'] = DepositMethod::whereStatus(1)->orderBy('is_eligible', 'desc')->get();
        $data['bankaccounts'] = WhiteListAccount::where('status', 'approved')->get();
        $data['type'] = $type;
        $activeBanks = BankAccount::where('status', 'active')->get();
        $data['bank'] = $activeBanks->isNotEmpty() ? $activeBanks->random() : null;   
        return view('deposits.add_method', $data);
    }
    
    public function save(Request $request)
    { 
        if($request->ajax())
        {
            $depositMethod = DepositMethod::findOrFail($request->id);
            $detail = isset($depositMethod->detail) ? $depositMethod->detail:'';
            $transaction_receipt_ref_no_format = !empty($depositMethod->transaction_receipt_ref_no_format) 
                                                    ? $depositMethod->transaction_receipt_ref_no_format:'';
            $is_eligible = !empty($depositMethod->is_eligible) 
                                                    ? $depositMethod->is_eligible:'';

            return response()->json(['detail'=>$detail, 'transaction_receipt_ref_no_format' => $transaction_receipt_ref_no_format, 'is_eligible' => $is_eligible]);
        }

        $rules = [
            'transaction_receipt_ref_no' => 'required',
            'amount' => 'required',
            'deposit_method_id' => 'required',
            'kyc' => 'required|file|mimes:pdf',
            'bank_account' => 'required',

        ];
        
        $message = [
            'transaction_receipt_ref_no.required' =>  'The Transaction Reference No field is required.'
        ];

        if ($request->hasFile('kyc')) {
            $file = $request->file('kyc');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
        } else {
            $filePath = null;
        }   
        $type = $request->input('balance_type');
        $request->validate($rules, $message);

        $existingDeposit = Deposit::where('transaction_receipt_ref_no', trim($request->transaction_receipt_ref_no))->first();

        if(!empty($existingDeposit)) {
            if($existingDeposit->transaction_state_id == 3) {
                flash(__('Already submitted deposit request and pending. Please wait for some time.'), 'danger');
                return redirect()->back()->withInput();
            }
            if($existingDeposit->transaction_state_id == 1) {
                flash(__('This Transaction Reference No is already used.'), 'danger');
                return redirect()->back()->withInput();
            }
        }

      
        $payment_method_id = DepositMethod::findOrFail($request->deposit_method_id);
        $currency_id = isset($payment_method_id->currency_id) ? $payment_method_id->currency_id:'';
        $currency = Currency::findOrFail($currency_id);
        $currency_symbol = isset($currency->symbol) ? $currency->symbol:'';
        $wallet = Wallet::where(['user_id'=>auth()->user()->id])->where(['currency_id'=>$currency_id])->first();
        if(!$wallet)
        {
            flash(__('Wallet not found!'), 'error');
            return redirect(route('mydeposits', app()->getLocale()));
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
        $bank_account = $request->bank_account;
        $deposited_to = $request->deposit_to;
        if ($bank_account) {
            $explode = explode('-',$bank_account);
            $bank_details = 'Name:-'.$explode[0].' -  Acc No: ' .$explode[1].'- IFSC: ' .$explode[2];
        }
        if ($deposited_to) {
            $explode = explode('-',$deposited_to);
            $deposited_to = 'Name:-'.$explode[0].' -  Account Number: ' .$explode[1].'- IFSC: ' .$explode[2];
        }
        
        $method->user_id = auth()->user()->id;
        $method->unique_transaction_id = $trx;
        $method->transaction_receipt_ref_no = isset($request->transaction_receipt_ref_no) ? trim($request->transaction_receipt_ref_no) : null;
        $method->net = $request->amount;
        $method->gross = $request->amount;
        $method->deposit_method_id = $request->deposit_method_id;
        $method->currency_id = $currency_id;
        $method->currency_symbol = $currency_symbol;
        $method->wallet_id = $wallet_id;
        $method->kyc_file_upload = $filePath;
        $method->type =$type;
        $method->bank_account = $bank_details;
        $method->deposited_to = $deposited_to;
        $method->save();
        
        flash(__('Deposit request submitted successfully!'), 'success');
        return redirect(route('mydeposits', app()->getLocale()));
    }
}
