<?php
namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Agent\Agent;
use App\Models\Setting;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
       
        view()->composer('*', function ($view)
        {   
            $setting = Setting::all();
            $agent = new Agent();
            $is_mobile = $agent->isMobile();
            $view->with('general_setting', $setting);
            $view->with('is_mobile', $is_mobile);
        });
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
