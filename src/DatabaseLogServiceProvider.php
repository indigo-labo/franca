<?php

namespace IndigoLabo\Franca;

use Illuminate\Support\ServiceProvider;
use IndigoLabo\Franca\Services\DatabaseLogService;

class DatabaseLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        DatabaseLogService::listen();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
