<?php

namespace App\Http\Controllers;


use Mail;
use App\User;
use App\Mail\resetEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Models\Transaction;
use \App\Models\TransactionState;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Auth;
use App\Jobs\SendTransactionMail;
use Log;

class LedgerController extends Controller
{
     
	public function index(Request $request)
	{
		$active = 'transaction';
        $page_title = 'All Transaction';
        $transactionStatus = TransactionState::query()->pluck('name', 'id');
        $todayStartUTC = now()->startOfDay()->setTimezone('UTC');
        $todayEndUTC = now()->endOfDay()->setTimezone('UTC');
        $transactions = Transaction::whereBetween('created_at', [$todayStartUTC, $todayEndUTC])->where('transaction_state_id', 1)->get();

        $manualDepositCount = $transactions->where('activity_title', 'Manual Deposit From Dashboard')->sum('net');
        $purchaseSaleCount = $transactions->where('activity_title', 'Manual Deposit')->sum('net');
        $wire_count = $transactions->where('activity_title', 'Wire-Transfer')->sum('net');
        
        $today_payout = $manualDepositCount + $purchaseSaleCount + $wire_count;
		return view('ledger.index', compact('active', 'page_title', 'transactionStatus','today_payout'));
	
	}

	public function transaction_list(Request $request)
	{
		$id = Auth::user()->id;

		if (!request()->ajax()) {
			$active = 'transaction';
            $page_title = 'All Transaction';
            $transactionStatus = TransactionState::query()->pluck('name', 'id');

            return view('ledger.index', compact('active', 'page_title', 'transactionStatus'));
        }
        $a = ['Purchase','Manual Deposit'];
        $data = Transaction::query()->with('User','Status', 'Currencie','Deposits','Requests')->where('user_id', '=' , $id)->whereNotIn('activity_title',$a)
            ->when(!empty(request()->transaction_state_id), function ($query) {
                return $query->where('transaction_state_id', request()->transaction_state_id);
            })
            ->when(!empty(request()->activity_type), function ($query) {
                if (request()->activity_type == 'UPI') {
                    return $query->where('activity_title', 'Sale');
                }elseif (request()->activity_type == 'Wallet Deposit') {
                   return $query->where('activity_title', 'Manual Deposit From Dashboard');
                }elseif (request()->activity_type == 'Settlement') {
                   return $query->where('activity_title', 'Manual Withdraw');
                }elseif (request()->activity_type == 'Sent') {
                   return $query->where('activity_title', 'Money Sent');
                }elseif (request()->activity_type == 'Received') {
                   return $query->where('activity_title', 'Money Received');
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
                dispatch(new SendTransactionMail($new_data));
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
            ->editColumn('money_flow', function ($data) {
                $badge = '';
                if ($data->money_flow === '+') {
                    $badge .= '<span class="badge badge-info" >'.$data->money_flow.'</span>';
                } else {
                    $badge .= '<span class="badge badge-danger" >'.$data->money_flow.'</span>';
                }
                return $badge;
            })
            ->editColumn('activity_title', function ($data) {
                if ($data->activity_title == 'Sale') {
                    $data->activity_title = 'Upi';
                }elseif ($data->activity_title == 'Manual Deposit From Dashboard') {
                   $data->activity_title = 'Wallet Deposit';
                }elseif ($data->activity_title == 'Manual Withdraw') {
                   $data->activity_title = 'Settlement';
                }elseif ($data->activity_title == 'Money Sent') {
                   $data->activity_title = 'Sent';
                }elseif ($data->activity_title == 'Money Received') {
                   $data->activity_title = 'Received';
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
                if($data && $data->Deposits && $data->Deposits->ag_bank_reference_no && $data->activity_title == 'Sale'){
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
            ->rawColumns(['status','money_flow','unique_transaction_id','ag_bank_reference_no','ref'])
			->toJson();
	
	}


}
