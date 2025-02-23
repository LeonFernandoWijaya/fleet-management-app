<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Gate::define('moduleAction', function ($user, string $moduleName, string $action) {
            return $user->hasModuleAction($moduleName, $action);
        });

        Blade::if('moduleAction', function (string $moduleName, string $action) {
            return Auth::user()->hasModuleAction($moduleName, $action);
        });
    }
}
