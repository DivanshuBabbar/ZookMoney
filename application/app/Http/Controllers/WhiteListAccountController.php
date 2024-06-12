<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WhiteListAccount;
use Auth;

class WhiteListAccountController extends Controller
{
     
	public function index(Request $request){

		$user_id = Auth::user()->id;
		$data = WhiteListAccount::where('user_id',$user_id)->get();
		return view('whitelistacount.index',compact('data'));
	}
	public function store(Request $request){


		$validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'required|string',
            'ifsc_number' => 'required|string', 
        ]);

		if ($validatedData) {
	
			$user_id = Auth::user()->id;
			$name = $request->name;
			$account_number = $request->account_number;
			$ifsc_number = $request->ifsc_number;
			$account_holder_name =  $request->account_holder_name;
			$save_details = new WhiteListAccount();
			$save_details->name = $name;
			$save_details->account_holder_name = $account_holder_name;
			$save_details->account_number = $account_number;
			$save_details->ifsc_number = $ifsc_number;
			$save_details->user_id = $user_id;
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
