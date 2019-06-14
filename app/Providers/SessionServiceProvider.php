<?php

namespace App\Providers;

use App\Libraries\Auth\FeelConnectionDatabaseSessionHandler;
use Illuminate\Support\ServiceProvider;


class SessionServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function register() {
        // 差し替え
        $this->app->session->extend('database', function ($app) {
            $lifetime   = $this->app->config->get('session.lifetime');
            $table      = $this->app->config->get('session.table');
            $connection = $app->app->db->connection($this->app->config->get('session.connection'));

            return new FeelConnectionDatabaseSessionHandler(
                $connection,
                $table,
                $lifetime,
                $this->app
                );
        });
    }
}
