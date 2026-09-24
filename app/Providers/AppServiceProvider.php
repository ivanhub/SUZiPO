<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Директива для роли URP (urp, urp admin, admin)
        Blade::if('urp', function () {
            return auth()->user() && auth()->user()->hasAnyRole(['urp', 'urp admin', 'admin']);
        });

        // Директива для роли OOO (ooo, ooo admin, ooo chief, admin)
        Blade::if('ooo', function () {
            return auth()->user() && auth()->user()->hasAnyRole(['ooo', 'ooo admin', 'ooo chief', 'admin']);
        });

        // Директива для роли ADMIN (только admin)
        Blade::if('admin', function () {
            return auth()->user() && auth()->user()->hasRole('admin');
        });

        // Директива для роли OOKOIT (ookoit, admin)
        Blade::if('ookoit', function () {
            return auth()->user() && auth()->user()->hasAnyRole(['ookoit', 'admin']);
        });

        // Директива для роли METODIST (metodist, admin)
        Blade::if('metodist', function () {
            return auth()->user() && auth()->user()->hasAnyRole(['metodist', 'admin']);
        });
    }
}