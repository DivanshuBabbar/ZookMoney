<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class BlockedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) 
        {
            if(Auth::User()->account_status != 1 ){
                Auth::logout();
                flash('Account has been blocked!','danger');
                return back();
            }
        }
        return $next($request);
    }
}
