<?php

namespace Mods\Backend;

use Illuminate\Routing\Router;
use Mods\Backend\Support\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Mods\Backend\Http\Controllers';

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
     * Define the "backend web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function registerBackendRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace
        ], function ($router) {
            require __DIR__.'/../routes/backend.php';
        });
    }
}
