<?php

namespace Haruncpi\LaravelApiMailer;

use Illuminate\Support\ServiceProvider;

class ApiMailerServiceProvider extends ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path()
        ], 'config');
    }


    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH . '/api-mailer.php',
            'api-mailer'
        );
    }
}
