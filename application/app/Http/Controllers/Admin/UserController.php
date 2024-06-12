<?php

namespace App\Http\Controllers\Admin;

use \App\User;
use \Illuminate\Http\Request;
use \App\Http\Controllers\Controller;
use TCG\Voyager\Models\Role;
use Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Jobs\SendUserListMail;
use Log;

class UserController extends Controller
{
	public function list(Request $request){
		// $data = DB::table('users')->get();
		// echo '<pre>';print_r($data);die();
		return view('notus.users.users')->with('users', User::paginate(10));
	}
	public function user_list()
	{

    if (!request()->ajax()) {

      $roles = Role::pluck('display_name', 'id')->all();
      $roles['merchant'] = 'merchant';
      $data['active'] = 'users';
      $data['user_types'] = $roles;
      $data['page_title'] = 'Users';
  
      return view('admin.users.user_list',$data);
    }

    $data = User::query()->with('role')->withCount('merchants')
      ->when(!empty(request()->user_role), function ($query) {
          if (request()->user_role !== 'merchant') {
              if (request()->user_role == 2) {
                  return $query->where('is_merchant', 0)->where('role_id','2');
              } else {
                  return $query->where('role_id', request()->user_role);
              }
          }
          return $query->where('is_merchant', 1);
      })
        ->when(!empty(request()->status), function ($query) {
          return $query->where('status', request()->status);
      });

    if (request()->multiple_email) {
       $emails = request()->multiple_email;
    }

    if (request()->openUserModal == '1') {
        $for_data = $data->get();
        $new_data = [];
        foreach ($for_data as $key => $value) {
            $new_data[] = $value;
        }
        $new_data['emails'] = $emails;
        try{
            dispatch(new SendUserListMail($new_data));
            Log::debug('Job dispatched successfully.');
        }catch (Exception $e) {
            Log::error('Error dispatching job: ' . $e->getMessage());
        }
    }else{
        $data = $data->latest();
    }

    return DataTables::eloquent($data)
        ->addIndexColumn()
        ->editColumn('avatar', function ($data) {
          $html = '';
          if(!empty($data->avatar)){
            $html .= "<img src='$data->avatar' alt='image' height='50px' width='50px'>";
          }

          return $html;
        })
        ->addColumn('role', function ($data) {
          $badge = '';
          if(!empty($data->role)){
              $badge .= '<span class="badge badge-info" >'.$data->role->display_name.'</span>';
          }
          if(!empty($data->is_merchant)){
              $badge .= '<span class="badge badge-dark" >Merchant ('.$data->merchants_count.')</span>';
          }
          return $badge;
        })
        ->addColumn('action', function ($data) {
            $editRoute = route('admin.user.edit',$data->id);
            $html = "<a href='$editRoute' class='btn btn-primary btn-sm'><i class='fa fa-edit'></i></a>";

            return $html;
        })
        ->editColumn('account_status', function ($data) {
              $isChecked = $data->account_status == 1 ? 'checked' : '';
              $html ='<label class="switch" for="checkMeStatus_'.$data->id.'"><input type="checkbox" class="user_data_toggle" id="checkMeStatus_'.$data->id.'" data-id='.$data->id.' '.$isChecked.'><span class="slider round"></span></label>';

              return $html;
          })
          ->editColumn('status', function ($data) {
            $html = '<div class="custom-dropdown">';
            $html .= '<select onchange="sendDropdownValue(this.value, ' . $data->id . ')">';
            $html .= '<option value="">--select--</option>';
            $html .= '<option value="spam" ' . ($data->status == 'spam' ? 'selected' : '') . '>Spam</option>';
            $html .= '<option value="inactive" ' . ($data->status == 'inactive' ? 'selected' : '') . '>Inactive</option>';
            $html .= '<option value="suspicious" ' . ($data->status == 'suspicious' ? 'selected' : '') . '>Suspicious</option>';
            $html .= '</select></div>';
            return $html;
        })        
        
        ->rawColumns(['role','action', 'avatar','account_status','status'])
        ->toJson();

	}
	public function user_edit($id=null)
	{
		$data['user'] = User::find($id);
		$data['active'] = 'users';
		$data['page_title'] = 'Users';
		return view('admin.users.edit',$data);
	}
	public function user_update($id=null,request $request)
	{
		$user = User::find($id);
		$validator = Validator::make($request->all(),[
            'verified'=>'required',
            'first_name'=>'required',
            'last_name'=>'required',
            'role_id'=>'required',
            'balance'=>'required',
            'customer_id'=>'required',
            'account_status'=>'required',
            'email'=>'required',
            'phonenumber'=>'required',
        ]);
        if(isset($request->avatar))
        {
        	$user->avatar = $request->avatar;
        }
        $user->verified = $request->verified; 
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->role_id = $request->role_id;
        $user->balance = $request->balance;
        $user->payout_balance = $request->payout_balance;
        $user->customer_id = $request->customer_id;
        $user->account_status = $request->account_status;
        $user->email = $request->email;
        $user->phonenumber = $request->phonenumber;
        $user->is_merchant = $request->is_merchant;
        $update = $user->update();
        if($update)
        {
        	flash('Update Successfully!','success');
        }
        else
        {
        	flash('Error Please try again!','error');
        }
        return redirect()->route('admin.user.list');

	}

  public function userKfcList()
  {
    $data['user_kyc'] = User::latest()->paginate(10);
    $data['page_title'] = 'User Kyc';
    $data['active'] = 'user_kyc';
    return view('admin.user_kyc.index',$data);
  }

  public function changeKycStatus($id =null ,$val=null)
  {
    if($id)
    {
      $user = User::find($id);
      $user->kyc_approved = $val;
      $user->update();
      if($user)
      {
        flash('Update Successfully!','success');
      }
      else
      {
        flash('Error Please try again!','error');
      }
      return back();
    }
  }
  public function accountStatus(Request $request)
  {
    $user_id = $request->user_id;
    $checked = $request->checked;

    $user = User::findorFail($user_id);
    if ($user) {
      $user->account_status = $checked;
      if ($checked == 0) {
        $user->payout_status = $checked;
        $user->payin_status = $checked;
      }
      $user->update(); 
      return response()->json(['message'=>'success'],200);
    }else{
      return response()->json(['message'=>'user not found'],404);

    }

  }

  public function changestatus(Request $request){
    $user_id = $request->input('id');
    $status = $request->input('selectedValue');

    $user = User::find($user_id);

    if ($user) {
        $user->status = $status;
        $user->save();

        return response()->json(['message' => 'Status updated successfully', 'status' => $status], 200);
    } else {
        return response()->json(['error' => 'User not found'], 404);
    }
}
}