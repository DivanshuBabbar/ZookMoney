<?php
namespace App\Http\Controllers\Admin;
use \Illuminate\Http\Request;
use \App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\TransactionState;
use App\User;
use App\Models\Wallet;
use App\Helpers\Money;
use App\Models\DepositMethod;
use App\Models\Transaction;
use App\Models\Payout;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\SendDepositMail;
use Log;

class DepositController extends Controller
{
	public function index()
    {
        if (!request()->ajax()) {
            $page_title ="Deposits";
            $active = 'deposits';
            $depositMethods = DepositMethod::query()->where('status', 1)->pluck('name', 'id');
            $transactionStatus = TransactionState::query()->pluck('name', 'id');

            return view('admin.deposits.index',compact('active','page_title', 'depositMethods', 'transactionStatus'));
        }

        $data = Deposit::query()->with('User','Method', 'Status')
                    ->when(!empty(request()->transaction_state_id), function ($query) {
                        return $query->where('transaction_state_id', request()->transaction_state_id);
                    })
                    ->when(!empty(request()->deposit_method_id), function ($query) {
                        return $query->where('deposit_method_id', request()->deposit_method_id);
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

        if (request()->openDepositModal == '1') {
            $for_data = $data->get();
            $new_data = [];
            foreach ($for_data as $key => $value) {
                $new_data[] = $value;
            }
            $new_data['emails'] = $emails;
            try{
                dispatch(new SendDepositMail($new_data));
                Log::debug('Job dispatched successfully.');
            }catch (Exception $e) {
                Log::error('Error dispatching job: ' . $e->getMessage());
            }
        }else{
            $data = $data->latest();
        }
        return DataTables::eloquent($data)
            ->addIndexColumn()
            ->addColumn('user_name', function ($data){
                return $data->User->name;
            })
            ->editColumn('transaction_receipt', function ($data) {
                $html = '';
                if(!empty($data->transaction_receipt)){
                    $url = url("assets/images/", $data->transaction_receipt);
                    $html = '<a href="'.$url.'" target="blank" >
                                <img src="'.$url.'" alt="image" class="avatar" style="width: 50px; height: 50px;">
                            </a>';
                }

                return $html;
            })        
            ->editColumn('created_at', function ($data) {
                return !empty($data->created_at) && ($data->created_at instanceof Carbon)? $data->created_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-';
            })
            ->addColumn('deposit_method_name', function ($data){
                return $data->Method->name;
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
            ->addColumn('action', function ($data) {

                $editRoute = route('admin.deposits.edit', $data->id);
                $viewRoute = route('admin.deposits.view', $data->id);
                return '<a href="'.$editRoute.'" class="btn btn-primary btn-sm">Edit</a>
                        <a href="'.$viewRoute.'" class="btn btn-warning btn-sm">View</a>';
            })
            ->rawColumns(['transaction_receipt', 'status','action'])
            ->toJson();
    }
    public function edit($id)
    {
        $active = 'deposits';
        $page_title = "Edit Deposits";
        $status = TransactionState::all();
        $deposits = Deposit::with('Method')->find($id);
        $kyc_url = "/application/storage/app/public/{$deposits->kyc_file_upload}"; 
        return view('admin.deposits.edit', compact('deposits', 'page_title', 'status', 'active', 'kyc_url'));
    }
    
    public function update(Request $request, $id)
    {
        $deposit = Deposit::find($id);

        $transaction_state_id = isset($request->transaction_state_id) ? $request->transaction_state_id:'';
        $currency_id = isset($deposit->currency_id) ? $deposit->currency_id:'';
        $user_id = isset($deposit->user_id) ? $deposit->user_id:'';
        $wallet_id = isset($deposit->wallet_id) ? $deposit->wallet_id:'';
        $amount = isset($deposit->net) ? $deposit->net:'';
        $trx = isset($deposit->unique_transaction_id) ? $deposit->unique_transaction_id:'';
        $user = User::find($user_id);
        $wallet = Wallet::find($wallet_id);
        
        $deposit_fee = 0;
        $total_fee = 0;
        $fee_in_amount = Money::getSetting('deposit_fixed_fee');
        $fee_in_percentage = Money::getSetting('deposit_percentage_fee');
        $total_fee_percentage = ($amount/100) * $fee_in_percentage;
        $total_fee = $total_fee_percentage + $fee_in_amount;
        $net_amount = ($amount - $total_fee);
        
        $transaction = $deposit->Transactions->first();
      
        if ($deposit->type == "main") {
            if (!empty($transaction)) {
                $transaction->transaction_state_id = $transaction_state_id;
                $transaction->balance = $wallet->fiat;
                $transaction->save();
    
                $wallet->fiat += $transaction->net;
                $wallet->save();
            } elseif ($transaction_state_id == 1) {
                $newTransaction = Transaction::create([
                    'user_id' => $user->id,
                    'entity_id' => $user->id,
                    'entity_name' => $user->name,
                    'request_id' => $deposit->request_id ?? null,
                    'transactionable_id' => $deposit->id,
                    'transactionable_type' => Deposit::class,
                    'transaction_state_id' => $transaction_state_id,
                    'money_flow' => '+',
                    'activity_title' => 'Manual Deposit From Dashboard',
                    'currency_id' => $wallet->currency->id,
                    'currency_symbol' => $wallet->currency->symbol,
                    'thumb' => $wallet->currency->thumb,
                    'gross' => $amount,
                    'fee' => $total_fee,
                    'net' => $net_amount,
                    'balance' => $wallet->fiat + $net_amount,
                ]);
                $wallet->fiat += $net_amount;
                $wallet->save();
            }
        } else {
            $string = strtoupper($this->random_strings(8));
    
                $newPayout = Payout::create([
                    'user_id' => $user->id,
                    'payout_id' => $string,
                    'transactionable_type' => 'Payout-Deposit',
                    'transaction_state_id' => $transaction_state_id,
                    'money_flow' => '+',
                    'currency_id' => $wallet->currency->id,
                    'currency_symbol' => $wallet->currency->symbol,
                    'gross' => $amount,
                    'fee' => $total_fee,
                    'net' => $net_amount,
                    'balance' => $wallet->payout_fiat + $net_amount,
                ]);
                $wallet->payout_fiat += $net_amount;
                $wallet->save();
                $user = User::where('id',$user_id)->first();
                $user->payout_balance += $net_amount;
                $user->save();
        }
    
        $deposit->transaction_state_id = $transaction_state_id;
        $deposit->save();
        flash('Update Successfully!', 'success');
        return redirect()->route('admin.deposits.list');
    }
    
    public function display($id)
    {
        $page_title ="Viewing Deposit";
        $active = 'deposits';
        $deposits = Deposit::find($id);
        return view('admin.deposits.display',compact('deposits','active','page_title'));
    }
    // public function  delete($id = null)
    // {
    //      $deposits = Deposit::find($id);
    //      $deposits->delete();
    //      flash('Delete Successfully!','danger');
    //      return redirect()->route('admin.deposits.list');
    // }

    public static function random_strings($length_of_string)
    {
        // md5 the timestamps and returns substring
        // of specified length
        return substr(md5(time()), 0, $length_of_string);
    }
}