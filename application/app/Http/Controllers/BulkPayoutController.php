<?php

namespace App\Http\Controllers;

use  App\Models\Bulk_Files;

use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Carbon;
use Auth;   

class BulkPayoutController extends Controller
{

    public function index()
    {
        $page_title = "Bulk Payout";
        $active = 'bulk_payout';
        return view('bulk_payout.index', compact('active', 'page_title'));
    }

    public function bulk_payout(Request $request){
            if (!request()->ajax()) {
                $page_title = "Bulk Payout";
                $active = 'bulk_payout';
                return view('bulk_payout.index', compact('active', 'page_title'));
            }
            $user_id = Auth::user()->id;
        
            $data = Bulk_Files::query()->where('user_id',$user_id)->latest();
            return DataTables::eloquent($data)
            ->editColumn('file_name', function ($data) {
                $badge = '<a href="/application/'.$data->path.'" target="_blank" style="color:blue;">View File</a>';
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
                    } else {
                        $color = 'warning';
                        $name = 'Pending';
                    }
                    $badge .= '<span class="badge badge-'.$color.'" >'.$name.'</span>';

                }
                return $badge;
            }) 
            ->editColumn('response_file', function ($data) {
                if (!empty($data->response_file)) {
                    return '<a href="/application/'.$data->response_file.'" target="_blank" style="color:blue;">View File</a>';
                }
                return "";
            })
            ->editColumn('created_at', function ($data) {
                return !empty($data->created_at) && ($data->created_at instanceof Carbon)? $data->created_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-';

            })
                ->addIndexColumn()
                ->rawColumns(['file_name','status','response_file','created_at'])
                ->toJson();
        }
}
