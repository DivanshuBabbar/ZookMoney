<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;
use \App\Models\Transaction;
use App\Models\TransactionState;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\SendTransactionMail;
use Log;

class TransactionController extends Controller
{
    public function index()
    {

        if (!request()->ajax()) {

            $active = 'transaction';
            $page_title = 'All Transaction';
            $transactionStatus = TransactionState::query()->pluck('name', 'id');

            return view('admin.transaction.transaction_list', compact('active', 'page_title', 'transactionStatus'));
        }
        
        $data = Transaction::query()->with('User','Status', 'Currencie','Deposits','Requests')
                ->when(!empty(request()->transaction_state_id), function ($query) {
                    return $query->where('transaction_state_id', request()->transaction_state_id);
                })
                  ->when(!empty(request()->userId), function ($query) {
                    return $query->where('user_id', request()->userId)->where('transaction_state_id','1')->where('chargeback_status','1');
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
                // SendTransactionMail::dispatch($new_data);
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
            ->editColumn('currency', function ($data) {
                return $data->Currencie->code;
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
            ->editColumn('entity_id', function ($data) {
                if ($data->entity_id) {
                    $m_id = bin2hex($data->entity_id);
                    if(strlen($data->entity_id) == 2){
                      $mid = $m_id.'000000000'.$data->entity_id;
                    }elseif(strlen($data->entity_id) == 3){
                      $mid = $m_id.'0000000'.$data->entity_id;
                    }else{
                      $mid = $m_id.'00000'.$data->entity_id;
                    }
                    return $mid;
                }else{
                    return 'N/A';
                }

            })
            ->editColumn('chargeback_status', function ($data) {
                $isChecked = $data->chargeback_status == 1 ? 'checked' : '';
                $html = '
                    <label class="switch" for="checkchargeback_'.$data->id.'">
                        <input type="checkbox" class="charge_data_toggle" id="checkchargeback_'.$data->id.'" data-id="'.$data->request_id.'" '.$isChecked.'>
                        <span class="slider round"></span>
                    </label>';
              
                return $html;
            })
            
            ->addColumn('qr_service_payload', function ($data) {
                if($data && $data->Deposits && $data->Deposits->qr_service_payload){
                    $json = json_decode($data->Deposits->qr_service_payload);
                    return $json->name;
                }else{
                    return 'N/A';
                }
                
            })
            ->addColumn('vpa', function ($data) {
                if($data && $data->Deposits && $data->Deposits->qr_service_payload){
                    $json = json_decode($data->Deposits->qr_service_payload);
                    return $json->vpa;
                }else{
                    return 'N/A';
                }
                
            })
            ->addIndexColumn()
            ->rawColumns(['status','money_flow','unique_transaction_id','ag_bank_reference_no','ref','entity_id','qr_service_payload','vpa','chargeback_status']) 
            ->toJson();
    }

    public function delete($id = null)
    {
        $transaction = Transaction::findorFail($id);
        $transaction->delete();
        flash('Deleted Successfully!','success');
        return redirect()->back();
    }

    public function chargeBack(Request $request) {
   
    $request_id = $request->request_id;
    $checked = $request->checked;

    $user = Transaction::where('request_id',$request_id)->first();
    if ($user) {
      $user->chargeback_status = $checked;
      $user->update(); 
      return response()->json(['message'=>'success'],200);
    }else{
      return response()->json(['message'=>'user not found'],404);

    }

  }
   
}
