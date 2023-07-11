<?php

/**
 * È il service provider per abilitare la app ad usare AdminPasswordResetTokenRepository
 *
 * @see App/Extensions/AdminPasswordResetTokenRepository
 * @author Luca Battarra
 */

namespace App\Providers;

use App\Extensions\AdminPasswordResetTokenRepository as DbRepository;
use App\Services\AdminPasswordBrokerManager; 
use Illuminate\Support\ServiceProvider;

class AdminPasswordResetServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPasswordBroker();

        $this->registerTokenRepository();
    }

   /**
      * Register the password broker instance.
      *
      * @return void
      */
     protected function registerPasswordBroker()
     {
         $this->app->singleton('auth.password', function ($app) {
             return new AdminPasswordBrokerManager($app);
         });

         $this->app->bind('auth.password.broker', function ($app) {
             return $app->make('auth.password')->broker();
         });
     }

    /**
     * Register the token repository implementation.
     *
     * @return void
     */
    protected function registerTokenRepository()
    {
        $this->app->singleton('auth.password.tokens', function ($app) {
            $connection = $app['db']->connection();

            // The database token repository is an implementation of the token repository
            // interface, and is responsible for the actual storing of auth tokens and
            // their e-mail addresses. We will inject this table and hash key to it.
            $table = $app['config']['auth.password.table'];

            $key = $app['config']['app.key'];

            $expire = $app['config']->get('auth.password.expire', 60);

            return new DbRepository($connection, $table, $key, $expire);
        });
    }

     /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['auth.password', 'auth.password.broker'];
    }
}
