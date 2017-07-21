<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->privateApiRoutes();

        $this->publicApiRoutes();

        $this->adminApiRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    protected function publicApiRoutes()
    {
      Route::prefix('api')
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/Api/publicapi.php'));
    }

    protected function privateApiRoutes()
    {
      Route::prefix('api')
            ->middleware('jwt.auth')
            ->namespace($this->namespace)
            ->group(base_path('routes/Api/private.php'));
    }

    protected function adminApiRoutes()
    {
      //di terima jika token yang di decrypt itu merupakan level = admin
      Route::prefix('api')
            ->middleware(['jwt.auth','adminApi'])
            ->namespace($this->namespace)
            ->group(base_path('routes/Api/admin.php'));
    }
}
