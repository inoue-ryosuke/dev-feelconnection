<?php namespace App\Providers;

use Collective\Annotations\AnnotationsServiceProvider as ServiceProvider;
use Illuminate\Foundation\Application;

class AnnotationsServiceProvider extends ServiceProvider
{

    /**
     * The classes to scan for route annotations.
     *
     * @var array
     */
    protected $scanRoutes = [];

    /**
     * The classes to scan for event annotations.
     *
     * @var array
     */
    protected $scanEvents = [];

    /**
     * Determines if we will auto-scan in the local environment.
     *
     * @var bool
     */
    protected $scanWhenLocal = false;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $scanRoutes = [
//            'App\Http\Controllers\Api\ApiTestController',
//            'App\Examples\Http\Controllers\HtmlController',
        ];
        // API
        $scanRoutes = array_merge($scanRoutes, [
            'App\Http\Controllers\Api\ApiTestController',
            'App\Http\Controllers\Api\AuthController',
            'App\Http\Controllers\Api\InviteController',
            'App\Http\Controllers\Api\InstructorController',
            'App\Http\Controllers\Api\MusicController',
            'App\Http\Controllers\Api\ReservationModalController',
            'App\Http\Controllers\Api\MailCheckController',
            'App\Http\Controllers\Api\MailAuthController',
            'App\Http\Controllers\Auth\LoginController',
            'App\Http\Controllers\Api\MemtypeChangeController'
        ]);

        // WEB
        //$scanRoutes = array_merge($scanRoutes, [
        //]);

        $this->scanRoutes = $scanRoutes;
        parent::__construct($app);
    }
}
