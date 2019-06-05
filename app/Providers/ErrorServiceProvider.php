<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class ErrorServiceProvider extends ServiceProvider {

    /**
     * Register any error handlers.
     *
     * @return void
     */
    public function boot() {
        // This is only if you use the Whoops error handler (install via composer).
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->bind( 'Illuminate\Contracts\Debug\ExceptionHandler', 'App\Library\Exception\ExceptionHandler' );
    }

}