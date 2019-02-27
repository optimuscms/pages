<?php

namespace Optimus\Pages;

use Illuminate\Support\ServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    protected $controllerNamespace = 'Optimus\Pages\Http\Controllers';

    public function boot()
    {
        $this->loadMigrationsFrom(
            __DIR__ . '/../database/migrations'
        );

        $this->registerAdminRoutes();
    }

    public function register()
    {
        $this->app->singleton(TemplateManager::class);
    }

    protected function registerAdminRoutes()
    {
        $this->app['router']
             ->name('admin.')
             ->namespace($this->controllerNamespace)
             ->middleware('web', 'auth:admin')
             ->group(function ($router) {
                 // Pages
                 $router->apiResource('pages', 'PagesController');
                 $router->patch('pages', 'PagesController@reorder');

                 // Templates
                 $router->apiResource('page-templates', 'PagesController')
                        ->only(['index', 'show']);
             });
    }
}
