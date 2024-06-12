<?php
namespace App\Http\Controllers\Admin;
use \App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\TransactionState;
use App\Models\WithdrawalMethod;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\SendWithdrawlMail;
use Log;

class WithdrawalController extends Controller
{
    public function index()
    {
        if (!request()->ajax()) {
            
            $page_title ="Withdrawals";
            $active = 'withdrawal';
            $withdrawMethods = WithdrawalMethod::query()->where('status', 1)->pluck('name', 'id');
            $transactionStatus = TransactionState::query()->pluck('name', 'id');
            
            return view('admin.withdrawal.index',compact('active','page_title', 'withdrawMethods', 'transactionStatus'));
        }

        $data = Withdrawal::query()->with('users','Method', 'Status', 'currency')
            ->when(!empty(request()->transaction_state_id), function ($query) {
                return $query->where('transaction_state_id', request()->transaction_state_id);
            })
            ->when(!empty(request()->withdrawal_method_id), function ($query) {
                return $query->where('withdrawal_method_id', request()->withdrawal_method_id);
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

        if (request()->openWithdrawlModal == '1') {
            $for_data = $data->get();
            $new_data = [];
            foreach ($for_data as $key => $value) {
                $new_data[] = $value;
            }
            $new_data['emails'] = $emails;
            try{
                dispatch(new SendWithdrawlMail($new_data));
                Log::debug('Job dispatched successfully.');
            }catch (Exception $e) {
                Log::error('Error dispatching job: ' . $e->getMessage());
            }
        }else{
            $data = $data->latest();
        }

        return DataTables::eloquent($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($data) {
                return !empty($data->created_at) ? $data->created_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-';
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

                $editRoute = route('admin.withdrawal.edit',['id' => $data->id]);
                $viewRoute = route('admin.withdrawal.detail',['id' => $data->id]);

                $html = '<div class="btn-group">';
                $html .= '<a href="'.$editRoute.'" class="btn btn-primary btn-sm">Edit</a>';
                $html .= '<a href="'.$viewRoute.'" class="btn btn-warning btn-sm">View</a>';
                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['status','action'])
            ->toJson();

    }
    public function edit($id =null)
    {
        $data['withdrawal'] = Withdrawal::findorFail($id);
        $data['active'] = 'withdrawal';

        return view('admin.withdrawal.edit_withdrawal',$data);
    }
    public function detail_view($id =null)
    {
        $data['withdrawal'] = Withdrawal::findorFail($id);
        $data['active'] = 'withdrawal';
        //echo '<pre>';print_r($data['withdrawal']);die();
        return view('admin.withdrawal.detail_view',$data);
    }
    public function withdrawal_update(Request $request,$id)
    {
        $withdrawal = Withdrawal::find($id);
        $transaction_state_id = isset($request->transaction_state_id) ? $request->transaction_state_id:'';
        $currency_id = isset($withdrawal->currency_id) ? $withdrawal->currency_id:'';
        $user_id = isset($withdrawal->user_id) ? $withdrawal->user_id:'';
        $wallet_id = isset($withdrawal->wallet_id) ? $withdrawal->wallet_id:'';
        $amount = isset($withdrawal->net) ? $withdrawal->net:'';
        $trx = isset($withdrawal->unique_transaction_id) ? $withdrawal->unique_transaction_id:'';
        $user = User::find($user_id);
        $wallet = Wallet::find($wallet_id);
        $transaction = Transaction::where(['request_id'=>$trx])->first();
        if($transaction_state_id == 2)
        {
            $wallet->fiat+=$amount;
            $wallet->save();
        }
        $transaction->transaction_state_id = $transaction_state_id;
        $transaction->save();
        $withdrawal->transaction_state_id = $request->transaction_state_id;
        $withdrawal->remarks =  $request->remarks;
        $withdrawal->update();
        flash(__('Done Successfully'), 'success');
        return redirect()->route('admin.withdrawal.list');
    }
}
