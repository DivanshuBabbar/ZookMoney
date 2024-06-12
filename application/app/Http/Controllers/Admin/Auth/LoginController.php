<?php
namespace App\Http\Controllers\Admin\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class LoginController extends Controller
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
    public $redirectTo = 'administrator/new_dashboard';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('admin.guest')->except('logout');
        $this->middleware('guest')->except('logout');
    }
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $page_title = "Admin Login";
        return view('admin.auth.login', compact('page_title'));
    }
    public function checklogin(Request $request)
    {
        $this->validateLogin($request);
        if($this->hasTooManyLoginAttempts($request)) 
        {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) 
        {
            if (auth()->user()->role_id == 1 || auth()->user()->role_id == 4) 
            {
                return redirect()->route('admin.new_dashboard');
            }
            else
            {
                Auth::logout();
                flash('You have no access for this','danger');
                return back();
            }
        }  
        else 
        {
            $this->incrementLoginAttempts($request);
            flash('Invalid credentials!','danger');
            return back();
        }
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
    public function resetPassword()
    {
        $page_title = 'Account Recovery';
        return view('admin.reset', compact('page_title'));
    }
}
