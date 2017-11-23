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

        // $this->publishes([
        //     __DIR__ . '/migrations/' => database_path('migrations')], 'migrations'
        // );
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        // $this->publishes([
        //     __DIR__.'/middleware/CheckAccess.php' => app_path('Http/Middleware/CheckAccess.php')], 'middleware'
        // );        
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