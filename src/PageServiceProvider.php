<?php

namespace Optimus\Pages;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    protected $controllerNamespace = 'Optimus\Pages\Http\Controllers';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->registerAdminRoutes();
    }

    protected function registerAdminRoutes()
    {
        Route::prefix('api')
             ->middleware('api', 'auth:admin')
             ->namespace($this->controllerNamespace)
             ->group(function () {
                 // Pages
                 Route::apiResource('pages', 'PagesController');
                 Route::patch('pages', 'PagesController@reorder');

                 // Templates
                 Route::get('page-templates', 'PageTemplatesController@index');
             });
    }
}
