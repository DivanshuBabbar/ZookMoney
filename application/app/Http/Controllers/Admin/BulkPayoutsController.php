<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Carbon;
use Auth;  
use  App\Models\Bulk_Files;
use App\Models\Payout;
use App\Models\Wallet;
use App\User;

class BulkPayoutsController extends Controller
{
    public function index(){

        if (!request()->ajax()) {
            $page_title = "Bulk Payout";
            $active = 'bulk_payout';
            return view('admin.bulk-payout.index', compact('active', 'page_title'));
        }
     
        $data = Bulk_Files::query()->with('User')->select('*')->latest();
        return DataTables::eloquent($data)
        ->addColumn('user_name', function ($data) {
            return $data->User->name;
        }) 
        ->editColumn('file_name', function ($data) {
            $badge = '<a href="/application/'.$data->path.'" target="_blank" style="color:blue;">View File</a>';
            return $badge;
        })
        ->editColumn('response_file', function ($data) {
            $badge = '';
            if (!empty($data->response_file)) {
            $badge = '<a href="/application/'.$data->response_file.'" target="_blank" style="color:blue;">View File</a>';            
            }
            return $badge;
        })
        ->addColumn('status', function ($data) {
            $badge = '';
            if(!empty($data->status)){
                if($data->status == 1) {
                    $color = 'info';
                    $name = 'Completed';
                } elseif ($data->status == 2) {
                    $color = 'danger';
                    $name = 'Canceled';
                }elseif ($data->status == 4) {
                    $color = 'info';
                    $name = 'Partially Completed';
                }  else {
                    $color = 'warning';
                    $name = 'Pending';
                }
                $badge .= '<span class="badge badge-'.$color.'" >'.$name.'</span>';

            }
            return $badge;
        }) 
        
        ->editColumn('created_at', function ($data) {
            return !empty($data->created_at) && ($data->created_at instanceof Carbon)? $data->created_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-';

        })
            ->addIndexColumn()
            ->rawColumns(['file_name','status','response_file','created_at'])
            ->toJson();
    }


    public function getdata(Request $request){

        $remark = $request->input('remark');
        $responseFile = $request->file('responseFile');
        $id = $request->input('requestId');
        $status_type = $request->input('status_type');
        $failed_amount = $request->input('failed_amount');

        $bulk_payout = Bulk_Files::where('id',$id)->first();
        $payout = Payout::where('payout_id',$bulk_payout->request_id)->first();

        if ($request->hasFile('responseFile')) {
            $file = $request->file('responseFile');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('bulk_files', $fileName, 'public');
            $filePath = '/storage/app/public/'.$filePath;
        }

        if($status_type == 'completed'){
            $payout->transaction_state_id = '1';
            $payout->save();

            $bulk_payout->response_file = $filePath;
            $bulk_payout->remarks = $remark;
            $bulk_payout->status = '1';
            $bulk_payout->save(); 
        }
        if($status_type == 'rejected'){

           $user_id = $bulk_payout->user_id;
           $amount = $bulk_payout->total_amount;
           $revert_amount = $payout->balance + $amount;

           $payout->balance = $revert_amount;
           $payout->transaction_state_id = '2';
           $payout->save();

           $wallet = Wallet::where('user_id',$user_id)->first();
           $wallet->payout_fiat += $amount;
           $wallet->save();

           $user = User::where('id',$user_id)->first();
           $user->payout_balance += $amount;
           $user->save();

           $bulk_payout->remarks = $remark;
           $bulk_payout->status = '2';
           $bulk_payout->save(); 
        }

        if($status_type == 'partially completed'){
            $user_id = $bulk_payout->user_id;

            $payout->balance += $failed_amount;
            $payout->transaction_state_id = '4';
            $payout->save();

            $wallet = Wallet::where('user_id',$user_id)->first();
            $wallet->payout_fiat += $failed_amount;
            $wallet->save();

            $user = User::where('id',$user_id)->first();
            $user->payout_balance += $failed_amount;
            $user->save();

            $bulk_payout->response_file = $filePath;
            $bulk_payout->remarks = $remark;
            $bulk_payout->status = '4';
            $bulk_payout->save(); 
        }
      
        return response()->json(['message' => 'Data received successfully']);
    }
}
