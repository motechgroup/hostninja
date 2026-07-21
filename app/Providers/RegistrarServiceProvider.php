<?php

namespace App\Providers;

use App\Services\Registrars\RegistrarManager;
use Illuminate\Support\ServiceProvider;

class RegistrarServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RegistrarManager::class, function ($app) {
            return new RegistrarManager();
        });
    }

    public function boot(): void
    {
        //
    }
}
