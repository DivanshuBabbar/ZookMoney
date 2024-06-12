<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\User;
use App\Models\Setting;
use App\Helpers\Money;
class SettingController extends Controller
{
    public function index()
    {
        $data['page_title'] = 'General Setting';
        $data['empty_message'] = 'No data available';
        $data['active'] = 'general_setting';
        $data['site_name'] = Money::getSetting('site_name');
        $data['site_icon'] = Money::getSetting('site_icon');
        $data['site_logo'] = Money::getSetting('site_logo');
        $data['site_url'] = Money::getSetting('site_url');
        $data['admin_email'] = Money::getSetting('admin_email');
        $data['stro_publickey'] = Money::getSetting('stro_publickey');
        $data['paypal_email'] = Money::getSetting('paypal_email');
        $data['stripe_secret'] = Money::getSetting('stripe_secret');
        $data['stripe_public'] = Money::getSetting('stripe_public');
        $data['flutter_public'] = Money::getSetting('flutter_public');
        $data['flutter_secret'] = Money::getSetting('flutter_secret');
        $data['paystack_public'] = Money::getSetting('paystack_public');
        $data['paystack_secret'] = Money::getSetting('paystack_secret');
        $data['razorpay_Secretkey'] = Money::getSetting('razorpay_Secretkey');
        $data['razorpay_keyId'] = Money::getSetting('razorpay_keyId');
        $data['instamojo_ApiKey'] = Money::getSetting('instamojo_ApiKey');
        $data['instamojo_AuthTokenKey'] = Money::getSetting('instamojo_AuthTokenKey');
        $data['Mollie_ApiKey'] = Money::getSetting('Mollie_ApiKey');
        $data['reloadly_client_id'] = Money::getSetting('reloadly_client_id');
        $data['reloadly_client_secret'] = Money::getSetting('reloadly_client_secret');
        $data['title'] = Money::getSetting('title');
        $data['merchant_fixed_fee'] = Money::getSetting('merchant_fixed_fee');
        $data['merchant_percentage_fee'] = Money::getSetting('merchant_percentage_fee');
        $data['mt_percentage_fee'] = Money::getSetting('mt_percentage_fee');
        $data['mt_fixed_fee'] = Money::getSetting('mt_fixed_fee');
        $data['fixed_fee'] = Money::getSetting('fixed_fee');
        $data['percent_fee'] = Money::getSetting('percent_fee');
        $data['deposit_fixed_fee'] = Money::getSetting('deposit_fixed_fee');
        $data['deposit_percentage_fee'] = Money::getSetting('deposit_percentage_fee');
        $data['withdraw_fixed_fee'] = Money::getSetting('withdraw_fixed_fee');
        $data['withdraw_percentage_fee'] = Money::getSetting('withdraw_percentage_fee');
        $data['payout_fixed_fee'] = Money::getSetting('payout_fixed_fee');
        $data['payout_percentage_fee'] = Money::getSetting('payout_percentage_fee');
        $data['wire_transfer_fixed_fee'] = Money::getSetting('wire_transfer_fixed_fee');
        $data['wire_transfer_percentage_fee'] = Money::getSetting('wire_transfer_percentage_fee');
        $data['time_status'] = Money::getSetting('time_status');
        $data['min_payin'] = Money::getSetting('min_payin');
        $data['max_payin'] = Money::getSetting('max_payin');
        $data['min_payout'] = Money::getSetting('min_payout');
        $data['max_payout'] = Money::getSetting('max_payout');
        $data['website'] = Money::getSetting('website');
        $data['ftd'] = Money::getSetting('ftd');
        $data['ftd_count'] = Money::getSetting('ftd_count');

        return view('admin.settings.index', $data);
    }
    public function post_setting(request $request)
    {
        $validator = Validator::make($request->all(),[
            'site_name'=>'required',
            'site_url'=>'required',
            'admin_email'=>'required',
            'title'=>'required',
            'site_icon'=>'required',
            'site_logo'=>'required',
            'stro_publickey'=>'required',
            'paypal_email'=>'required',
            'stripe_secret'=>'required',
            'stripe_public'=>'required',
            'flutter_public'=>'required',
            'flutter_secret'=>'required',
            'Mollie_ApiKey'=>'required',
            'paystack_secret'=>'required',
            'paystack_public'=>'required',
            'instamojo_ApiKey'=>'required',
            'instamojo_AuthTokenKey'=>'required',
            'razorpay_keyId'=>'required',
            'razorpay_Secretkey'=>'required',
            'reloadly_client_id'=>'required',
            'reloadly_client_secret'=>'required',
        ]);
        $value = $request->all();
        $flag = 0;
        foreach($value['setting'] as $key => $value)
        {
            $setting = Setting::where('key',$key)->first();
            if(empty($setting))
            {
                $setting = new Setting();
            }
            $setting->value =  $value;
            $setting->key = $key;
            $setting->save();
            if($setting->id)
            {
                $flag = 1;
            }
        }
        if($flag == 1)
        {
            flash('Done Successfully!','success');
        }
        else
        {
            flash('Error Please try again!','error');
        }
        return redirect()->route('admin.setting');
    }
}