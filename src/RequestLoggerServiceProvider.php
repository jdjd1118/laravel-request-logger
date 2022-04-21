<?php

namespace AlwaysOpen\RequestLogger;

use AlwaysOpen\RequestLogger\Console\Commands\MakeRequestLogTable;
use Illuminate\Support\ServiceProvider;

class RequestLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/request-logger.php' => config_path('request-logger.php'),
            ], 'config');

            $this->commands([
                MakeRequestLogTable::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/request-logger.php', 'request-logger');
    }
}
