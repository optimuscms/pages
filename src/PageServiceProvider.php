<?php

namespace Optimus\Pages;

use Illuminate\Support\Facades\Route;
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

    protected function registerAdminRoutes()
    {
        Route::name('admin.')
             ->prefix('api')
             ->namespace($this->controllerNamespace)
             // ->middleware('web', 'auth:admin')
             ->group(function () {
                  // Pages
                  Route::apiResource('pages', 'PagesController');
                  Route::patch('pages', 'PagesController@reorder');

                  // Templates
                  Route::apiResource('page-templates', 'TemplatesController')
                       ->only(['index', 'show']);
             });
    }
}
