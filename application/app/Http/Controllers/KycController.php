<?php
namespace App\Http\Controllers;
use App\User;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\AirtimeTransaction;
use App\Models\Transaction;
use Session;
use Image;
use File;
use Validator;
use App\Models\Currency;
use App\Models\Wallet;
use App\Helpers\Money;
class KycController extends Controller
{
    public function __construct()
    {
        
    }
    public function kyc_setting()
    {
        $data['page_title'] = "KYC Setting";
        $data['user'] = Auth::user();
        $data['user_detail'] = User::find(auth()->user()->id);
        // dd($data['user_detail']);
        return view('users.kyc_setting', $data);
    }
    public function submitKyc(Request $request)
    {
        $request->validate([
            'govt_id_card_front' => 'mimes:png,jpg,jpeg,pdf,PDF',
            'govt_id_card_back' => 'mimes:png,jpg,jpeg,pdf,PDF',
            'selfi' => 'mimes:png,jpg,jpeg,pdf,PDF',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        $user = Auth::user();
        if($request->hasFile('govt_id_card_front')) 
        {
            $image = $request->file('govt_id_card_front');
            $filename = rand(1000, 9999).time().'.'.$image->getClientOriginalExtension();
            $image->move('assets/images/user/kyc',$filename);
            $user->govt_id_card_front = $filename;
        }
        if($request->hasFile('govt_id_card_back')) 
        {
            $image = $request->file('govt_id_card_back');
            $filename = rand(1000, 9999).time().'.'.$image->getClientOriginalExtension();
            $image->move('assets/images/user/kyc',$filename);
            $user->govt_id_card_back = $filename;
        }
        if($request->hasFile('selfi')) 
        {
            $image = $request->file('selfi');
            $filename = rand(1000, 9999).time().'.'.$image->getClientOriginalExtension();
            $image->move('assets/images/user/kyc',$filename);
            $user->selfi = $filename;
        }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->save();
        flash(__('KYC Updated successfully.') , 'success');
        return  back();
    }
}