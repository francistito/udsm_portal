<?php

namespace App\Providers;

use App\Repositories\System\CodeValueRepository;
use App\Services\Access\Access;
use App\Services\Sysdef\System;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //


        $this->app->bind('access', function ($app) {
            return new Access();
        });
        $this->app->bind('code_value', function ($app) {
            return new CodeValueRepository();
        });
        $this->app->bind('sysdef', function ($app) {
            return new System();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
