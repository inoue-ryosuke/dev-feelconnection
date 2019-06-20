<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;

class SftpServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      Storage::extend('sftp', function ($app, $config) {
        return new Filesystem(new SftpAdapter([
                'host'        => $config['host'],
                'port'        => $config['port'],
                'username'    => $config['username'],
                'password'    => $config['password'],
                'privateKey'  => $config['privateKey'],
                'root'        => $config['root'],
                'timeout'     => $config['timeout'],
            ]));
        });
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
