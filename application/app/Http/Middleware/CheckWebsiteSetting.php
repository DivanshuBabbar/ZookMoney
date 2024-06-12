<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting; 

class CheckWebsiteSetting
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
        $setting = Setting::where('key', 'website')->first();

        if (!$setting || $setting->value == 'enabled') {
            abort(404);
        }
        return $next($request);
    }
}
