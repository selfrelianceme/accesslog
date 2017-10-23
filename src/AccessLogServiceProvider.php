<?php

namespace Selfreliance\AccessLog;

use Illuminate\Support\ServiceProvider;

class AccessLogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->app->make('Selfreliance\AccessLog\AccessLogController');

        $this->loadViewsFrom(__DIR__.'/views', 'accesslog');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}