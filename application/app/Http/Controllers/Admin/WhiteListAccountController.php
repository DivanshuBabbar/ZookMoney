<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use \App\Models\WhiteListAccount;
use \App\User;

use Illuminate\Support\Carbon;


class WhiteListAccountController extends Controller
{   

	public function index()
    {

        $page_title = "WhiteList Bank Accounts";
        $active = 'whitelist_account';
        $user = WhiteListAccount::pluck('user_id');
        $user_name = User::whereIn('id',$user)->pluck('id','name');

        return view('admin.whitelistacount.list', compact('active', 'page_title','user_name'));
        
    }

    public function list(Request $request)
    {

	    $data = WhiteListAccount::query()->with('User')->orderBy('status')
			->when(!empty(request()->user_role), function ($query) {
			  return $query->where('user_id', request()->user_role);
			})
			->when(!empty(request()->status), function ($query) {
			  return $query->where('status', request()->status);
			});

	    return DataTables::eloquent($data)
	        ->addIndexColumn()
	         ->editColumn('user_name', function ($data) {
	            return $data->User->name;
	        })
	      	->editColumn('user_email', function ($data) {
	            return $data->User->email;
	        })
	        ->editColumn('created_at', function ($data) {
	            return !empty($data->created_at) && ($data->created_at instanceof Carbon)? $data->created_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-';
	        })
	        ->editColumn('status', function ($data) {
            $html = '<div class="custom-dropdown">';
            $html .= '<select onchange="sendDropdownValue(this.value, ' . $data->id . ')">';
            $html .= '<option value="">--select--</option>';
            $html .= '<option value="pending" ' . ($data->status == 'pending' ? 'selected' : '') . '>Pending</option>';
            $html .= '<option value="approved" ' . ($data->status == 'approved' ? 'selected' : '') . '>Approved</option>';
            $html .= '<option value="rejected" ' . ($data->status == 'rejected' ? 'selected' : '') . '>Rejected</option>';
            $html .= '</select></div>';
            return $html;
        })      
	        ->rawColumns(['user_name','created_at','user_email','status'])
	        ->toJson();
    }

    public function updatestatus(Request $request)
    {
    	$id = $request->id;
    	$status = $request->selectedValue;

    	$save = WhiteListAccount::findorFail($id);
    	$save->status = $status;
    	$save->save();

        return response()->json(['suucess' => 'Status Updated Successfully'], 200);
    }

}