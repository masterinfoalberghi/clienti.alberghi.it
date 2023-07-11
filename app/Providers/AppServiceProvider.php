<?php
namespace App\Providers;

use Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Validator;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
    {
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() 
        {

        if (env('APP_HTTPS')) {
            
            $this->app['request']->server->set('HTTPS', 'on');
        
        }
            
        Validator::resolver(function ($translator, $data, $rules, $messages) 
            {
            return new CustomValidator($translator, $data, $rules, $messages);
            });


        // Binding eloquent.simple to our EloquentSimpleUserProvider
        Auth::provider('eloquent.simple', function() {
                return new EloquentAdminUserProvider;
        });


        Blade::component('components.log_view', 'log_view');


        Paginator::useBootstrap();
        
        }
    
    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */

    public function register() 
    {
        $this->app->bind('Illuminate\Contracts\Auth\Registrar', 'App\Services\Registrar');
        //if (Config::get('app.env') == 'production' && Config::get("app.ssl")) 
        if (env('APP_HTTPS')) {
              $this->app['url']->forceScheme('https');
        }
    }
}
