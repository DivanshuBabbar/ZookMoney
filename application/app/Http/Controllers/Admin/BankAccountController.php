<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use \App\Models\BankAccount;
use \App\User;

use Illuminate\Support\Carbon;


class BankAccountController extends Controller
{   

	public function index()
    {
    	$page_title = "Our Bank Accounts";
        $active = 'our_bank_account';
        $user = BankAccount::all();
       
        return view('admin.bank-account.index', compact('active', 'page_title'));
     
    }

    public function list(Request $request)
    {

	    $data = BankAccount::query()->orderBy('status');

	    return DataTables::eloquent($data)
	        ->addIndexColumn()
	        ->editColumn('created_at', function ($data) {
	            return !empty($data->created_at) && ($data->created_at instanceof Carbon)? $data->created_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-';
	        })
	        ->editColumn('status', function ($data) {
            $html = '<div class="custom-dropdown">';
            $html .= '<select onchange="sendDropdownValue(this.value, ' . $data->id . ')">';
            $html .= '<option value="">--select--</option>';
            $html .= '<option value="inactive" ' . ($data->status == 'inactive' ? 'selected' : '') . '>Inactive</option>';
            $html .= '<option value="active" ' . ($data->status == 'active' ? 'selected' : '') . '>Active</option>';
            $html .= '</select></div>';
            return $html;
        })      
	        ->rawColumns(['created_at','status'])
	        ->toJson();
    }

    public function updatestatus(Request $request)
    {
    	$id = $request->id;
    	$status = $request->selectedValue;

    	$save = BankAccount::findorFail($id);
    	$save->status = $status;
    	$save->save();

        return response()->json(['suucess' => 'Status Updated Successfully'], 200);
    }

    public function store(Request $request){


		$validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'required|string',
            'ifsc_number' => 'required|string', 
			'account_type' => 'required|string|max:255',
        ]);

		if ($validatedData) {
	
			$name = $request->name;
			$account_number = $request->account_number;
			$ifsc_number = $request->ifsc_number;
			$account_type = $request->account_type; 

			$save_details = new BankAccount();
			$save_details->name = $name;
			$save_details->account_type = $account_type ;
			$save_details->account_number = $account_number;
			$save_details->ifsc_number = $ifsc_number;
			$save_details->save();

			if ($save_details) {
				return response()->json(['success'=>'success'],200);
			}else{
				return response()->json(['error'=>'failed'],403);
			}
		}else{
			return response()->json(['error'=>'failed'],403);
		}
	}



}