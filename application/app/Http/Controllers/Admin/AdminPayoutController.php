<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use \App\Models\Payout;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\SendPayoutMail; 
use \Illuminate\Http\Request; 
use Log;

class AdminPayoutController extends Controller
{
    public function index()
    {
        $active = 'payout';
        $page_title = 'All Payout';
      
        return view('admin.payout.index', compact('active', 'page_title'));
    }

    public function getPayoutList()
    {
       if (!request()->ajax()) {
            $active = 'payout';
            $page_title = 'All Payout';
          
            return view('admin.payout.index', compact('active', 'page_title'));
        }

       
        $data = Payout::query()->with('User')->orderBy('payout.id','desc')
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
                dispatch(new SendPayoutMail($new_data));
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
                    } else {
                        $color = 'warning';
                        $name = 'Pending';
                    }
                    $badge .= '<span class="badge badge-'.$color.'" >'.$name.'</span>';

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
            
            ->editColumn('created_at', function ($data) {
                return !empty($data->created_at) && ($data->created_at instanceof Carbon)? $data->created_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-';

            })
            ->addIndexColumn()
            ->rawColumns(['status','money_flow'])
            ->toJson();
    }

    public function getlistbypayoutgroup(Request $request){
        
        $payoutGroupId = $request->input('payout_group_id');
        $UserId = $request->input('user_id');
        $payout_id = $request->input('payout_id');
        $payouts = Payout::where('payout_group_id', $payoutGroupId)->where('user_id',$UserId)->get();
        $response = [
            'payouts' => $payouts,
            'payoutGroupId' => 'Payout Group ID :' . $payoutGroupId,
        ];
        
            return response()->json($response);
        }
}
