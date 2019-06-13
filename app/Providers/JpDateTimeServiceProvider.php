<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class JpDateTimeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'JpDateTime',
            'App\Libraries\Common\JpDateTime'
        );
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
