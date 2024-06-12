<?php

namespace App\Http\Controllers;

use Mail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Models\TransactionLogs;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Auth;


class TransactionLogsController extends Controller
{
     
	public function index(Request $request)
	{
		$active = 'transaction logs';
        $page_title = 'All Transaction Logs';
        $transactionStatus = TransactionLogs::get();
        $transactionStatus = $transactionStatus->unique('ref');

		return view('logs.index', compact('active', 'page_title', 'transactionStatus'));
	
	}

    public function transaction_logs(Request $request)
    {

        $id = Auth::user()->id;

        if (!request()->ajax()) {
            $active = 'transaction logs';
            $page_title = 'All Transaction Logs';
            $transactionStatus = TransactionLogs::get();
            $transactionStatus = $transactionStatus->unique('ref');

            return view('logs.index', compact('active', 'page_title', 'transactionStatus'));
        }
       
        $data = TransactionLogs::groupBy('ref')->where('user_id', '=' , $id)
            ->when(!empty(request()->transaction_state_id), function ($query) {
                return $query->where('ref', request()->transaction_state_id);
            })
            ->when(!empty(request()->created_at_from), function ($query) {
                return $query->whereDate('created_at', '>=' ,date('Y-m-d', strtotime(request()->created_at_from)));
            })
            ->when(!empty(request()->created_at_to), function ($query) {
                return $query->whereDate('created_at', '<=' ,date('Y-m-d', strtotime(request()->created_at_to)));
            });

        return DataTables::eloquent($data)
            ->addColumn('view', function ($data) {
                $badge = '<a href="javascript:void(0)" onClick="getRef('.$data->ref.')" data-id="'.$data->ref.'"><i class="icon-folder-alt"></i></a>';
                return $badge;
            })
            ->editColumn('created_at', function ($data) {
                return !empty($data->created_at) && ($data->created_at instanceof Carbon)? $data->created_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-';

            })
            ->addIndexColumn()
            ->rawColumns(['view'])
            ->toJson();

    }

    public function show_logs(Request $request)
    {
        $ref = $request->ref;
        if($ref){
            $data = TransactionLogs::where('ref', '=' , $ref)->get();
            foreach ($data as $key => $value) {
                $value['user_agent'] = $this->getBrowser($value['user_agent']);
            }
        }else{
            $data = [];
        }
        
        return $data;
    }
    public static function getBrowser($user_agent) 
    { 

        $u_agent = $user_agent;
        $bname = 'Unknown';

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
        $bname = 'Internet Explorer';
        $ub = "MSIE";
        }elseif(preg_match('/Firefox/i',$u_agent)){
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
        }elseif(preg_match('/OPR/i',$u_agent)){
        $bname = 'Opera';
        $ub = "Opera";
        }elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
        $bname = 'Google Chrome';
        $ub = "Chrome";
        }elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
        $bname = 'Apple Safari';
        $ub = "Safari";
        }elseif(preg_match('/Netscape/i',$u_agent)){
        $bname = 'Netscape';
        $ub = "Netscape";
        }elseif(preg_match('/Edge/i',$u_agent)){
        $bname = 'Edge';
        $ub = "Edge";
        }elseif(preg_match('/Trident/i',$u_agent)){
        $bname = 'Internet Explorer';
        $ub = "MSIE";
        }

        return $bname;
        
    } 

}
