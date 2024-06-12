<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\MainCurrency;
use App\User;
use App\Models\Wallet;
use App\Models\TransferMethod;
use App\Models\Otp;
use App\Models\Currency;
use Propaganistas\LaravelPhone\PhoneNumber;
use Mail;
use Storage;
use App\Mail\otpEmail;



class ResellerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    // public $redirectTo = 'admin';
    public $redirectTo = '/';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
     
        $this->middleware('guest')->except('logout');
    }
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showResellerForm()
    {
        $page_title = "Reseller Login";
        return view('auth.reseller', compact('page_title'));
    }

    public function showResellerRegistrationForm(Request $request, $lang)
    {
        $countries = Country::all();
        return view('auth.resellerForm')->with('countries', $countries);
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
            'password_confirmation' =>  'required|same:password',
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
            'currency_id'   =>   $currency->id,
            'whatsapp'  =>  $number,
            'phonenumber'   =>  $request->phone,
            'role_id'  => 5, //reseller
            'verification_token'  => str_random(40),
            'is_merchant' => 1,
        ]);
        
        $generated_otp = $this->randInt(6) . '';

        if ($user) {
            $newwallet = wallet::create([
                'is_crypto' =>  $currency->is_crypto,
                'user_id'   => $user->id,
                'amount'    =>  0,
                'currency_id'   => $currency->id,
                'accont_identifier_mechanism_value' => 'mimic adress',
                'transfer_method_id' => $transferMethod->id
            ]);

            $user->wallet_id = $newwallet->id;

            $newwallet->TransferMethods()->attach($transferMethod, ['user_id'=>$user->id,'adress' => 'mimic adress']);

            $user->save();


            $Otp = Otp::create([
                'user_id'   => $user->id,
                'otp'   => password_hash($generated_otp, PASSWORD_DEFAULT)
            ]);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            
            //Send otp Mail
            Mail::send(new otpEmail( $user->email, $generated_otp));
            
            return redirect(app()->getLocale().'/home');

        }

        return redirect(app()->getLocale().'/');
    }
    private function randInt($digits)
    {
       return rand(pow(10, $digits-1), pow(10, $digits)-1);
    }
 }
