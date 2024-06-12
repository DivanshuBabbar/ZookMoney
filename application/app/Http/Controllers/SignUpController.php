<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Auth;
use Twilio;
use Mail;
use Storage;
use App\User;
use App\Models\Wallet;
use App\Models\TransferMethod;
use App\Models\Otp;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Mail\verifyEmail;
use App\Mail\otpEmail;
use App\Mail\TestMail;
use App\Mail\userRegisterEmail;
use Propaganistas\LaravelPhone\PhoneNumber;
use App\Models\MainCurrency;

class SignUpController extends Controller
{


    public function showRegistrationForm(Request $request, $lang)
    {
        $countries = Country::all();
        return view('auth.register')->with('countries', $countries);
    }

	public function register(Request $request, $lang){
        
        $main_currency = MainCurrency::with('currency')->first();
		$currency = $main_currency->currency;

        $transferMethod = TransferMethod::where('currency_id', $currency->id)->where('is_system', 1)->first();

        if( $transferMethod == null ){
            dd('no tr for mainc');
        }

		$this->validate($request, [
            'email' => 'required|unique:users,email|email|max:255',
            'name'  =>  'required|unique:users,name|alpha_dash|min:5',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'password'  =>  'required|min:6',
            'password_confirmation'	=>	'required|same:password',
            'phone' =>  'required|phone:US,CA,AF,AL,DZ,AS,AD,AO,AI,AQ,AG,AR,AM,AW,AU,AT,AZ,BS,BH,BD,BB,BY,BE,BZ,BJ,BM,BT,BO,BA,BW,BV,BR,IO,BN,BG,BF,BI,KH,CM,CV,KY,CF,TD,CL,CN,CX,CC,CO,KM,CG,CK,CR,HR,CU,CY,CZ,CD,DK,DJ,DM,DO,TP,EC,EG,SV,GQ,ER,EE,ET,FK,FO,FJ,FI,FR,FX,GF,PF,TF,GA,GM,GE,DE,GH,GI,GR,GL,GD,GP,GU,GT,GN,GW,GY,HT,HM,HN,HK,HU,IS,IN,ID,IR,IQ,IE,IL,IT,CI,JM,JP,JO,KZ,KE,KI,KP,KR,KW,KG,LA,LV,LB,LS,LR,LY,LI,LT,LU,MO,MK,MG,MW,MY,MV,ML,MT,MH,MQ,MR,MU,TY,MX,FM,MD,MC,MN,MS,MA,MZ,MM,NA,NR,NP,NL,AN,NC,NZ,NI,NE,NG,NU,NF,MP,NO,OM,PK,PW,PA,PG,PY,PE,PH,PN,PL,PT,PR,QA,SS,RE,RO,RU,RW,KN,LC,VC,WS,SM,ST,SA,SN,RS,SC,SL,SG,SK,SI,SB,SO,ZA,GS,ES,LK,SH,PM,SD,SR,SJ,SZ,SE,CH,SY,TW,TJ,TZ,TH,TG,TK,TO,TT,TN,TR,TM,TC,TV,UG,UA,AE,GB,UM,UY,UZ,VU,VA,VE,VN,VG,VI,WF,EH,YE,YU,ZR,ZM,ZW,mobile|unique:users,phonenumber|min:6',
            'CC'    =>  'required_with:phone|exists:countries,code',
            'terms' => 'required'
        ]);

        $number = (string) PhoneNumber::make($request->phone, $request->CC); 

        $user = User::create([
            'name'  => $request->name,
            'first_name'  => $request->first_name,
            'last_name'  => $request->last_name,
            'email' =>  $request->email,
            'avatar'    => Storage::url('users/default.png'),
            'password'  =>  bcrypt($request->password),
            'currency_id'	=>	 $currency->id,
            'whatsapp'  =>  $number,
            'phonenumber'   =>  $request->phone,
            'is_merchant'   =>  ($request->is_merchant == '1') ? intval($request->is_merchant) : 0,
            'verification_token'  => str_random(40),
            'white_label_status' => 0,
            'payout_status' => 0,

        ]);

        // $generated_otp = $this->randInt(6) . '';

        if ($user) {
            $newwallet = wallet::create([
                'is_crypto' =>  $currency->is_crypto,
                'user_id'	=> $user->id,
                'amount'	=>	0,
                'currency_id'	=> $currency->id,
                'accont_identifier_mechanism_value' => 'mimic adress',
                'transfer_method_id' => $transferMethod->id
            ]);

            $user->wallet_id = $newwallet->id;

            $newwallet->TransferMethods()->attach($transferMethod, ['user_id'=>$user->id,'adress' => 'mimic adress']);

            $user->save();


            // $Otp = Otp::create([
            //     'user_id'   => $user->id,
            //     'otp'   => password_hash($generated_otp, PASSWORD_DEFAULT)
            // ]);
        }
   

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $mail = ['alliance@zookpe.com','info@zookpe.com'];
            
            foreach($mail as $value){
                Mail::send(new userRegisterEmail($value, $user));
            }

            return redirect(app()->getLocale().'/home');
            
            //Send otp Mail
            
            // Mail::send(new otpEmail( $user->email, $generated_otp));

            //Send otp SMS

           // Twilio::message($user->phonenumber, array(
           //      'body' => __('Your ') . general_setting('site_name') . __(' one time password (OTP) is :  ') . $generated_otp . __('   Do not share this code with others.'),
           //      'SERVICE SID'  =>  'Envato',
           //  ));
            
            // return redirect(app()->getLocale().'/home');

        }

        return redirect(app()->getLocale().'/');
	}

    public function verifyEmail(Request $request, $lang, $email, $token){
        
        if ($email) {
           
           $user = User::where('email', $email)->where('verified', 0)->first();

           if (!is_null($user) and $user->verification_token == $token) {
               
                $user->verified = 1;
                $user->verification_token = NULL;
                $user->save();
           }

           if(isset($request->type) && $request->type == '1'){
            return response()->json(['message' => 'success'],200);
           }

           return redirect(app()->getLocale().'/home');
        }
    }

    public function postOtp(Request $request, $lang){

        $this->validate($request, [
            'otp'   =>  'required|numeric'
        ]);

        $otp = Otp::where('user_id', Auth::user()->id)->orderby('id', 'desc')->first();

        if (is_null($otp)) {
           abort(404);
        }
        if (password_verify($request->otp, $otp->otp)) {
            Auth::user()->verified = 1;
            Auth::user()->save();
            return redirect(app()->getLocale().'/home');
        }
        flash('Invalid One Time Password', 'danger');
        return back();
    }

    public function resendActivactionLink(Request $request, $lang){
        
        $string = str_random(40);
       
        $user = Auth::user();
        $user->verification_token = $string;
        $user->save();

        Mail::send(new VerifyEmail($user->email));

        flash(__('activation link succesfuly sent'), 'success');

       return back();
    }

    public function TestMail(Request $request, $lang){
        
        return view('otp.index');
    }

    public function OTP(Request $request, $lang){
 
        if (Auth::user()->verified) {
            return redirect(app()->getLocale().'/home');
        }
        return view('otp.index');
    }

    public function OTPresend(Request $request, $lang){
        if (Auth::user()->verified) {
            return redirect(app()->getLocale().'/home');
        }

        $generated_otp = $this->randInt(6) . '';

        $Otp = Otp::create([
                'user_id'   => Auth::user()->id,
                'otp'   => password_hash($generated_otp, PASSWORD_DEFAULT)
        ]);


        //Send otp Mail
        Mail::send(new otpEmail( Auth::user()->email, $generated_otp));

        flash(__('Check your email inbox for your (otp)'), 'info');

        if(!empty($request->type && $request->type == 1)){
            return response()->json(['url'=>'otp/blade','type'=>'1'],200);
        }
        return back();
    }

    private function randInt($digits){

        return rand(pow(10, $digits-1), pow(10, $digits)-1);

        // return str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

    }

 

}