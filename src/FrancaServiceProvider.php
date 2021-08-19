<?php

namespace IndigoLabo\Franca;

use Illuminate\Support\ServiceProvider;

class FrancaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once($filename);
        }
        $this->publishes([
            __DIR__.'/../config/basic_auth.php' => config_path('basic_auth.php'),
            __DIR__.'/../config/secure_protocol.php' => config_path('secure_protocol.php'),
        ]);
        $this->mergeConfigFrom(__DIR__.'/../config/basic_auth.php', 'basic_auth');
        $this->mergeConfigFrom(__DIR__.'/../config/secure_protocol.php', 'secure_protocol');

        $this->loadRoutesFrom(__DIR__.'/../routes/franca.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'franca');
    }
}
