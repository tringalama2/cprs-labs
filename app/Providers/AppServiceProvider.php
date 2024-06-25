<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public const ADMIN_HOME = '/admin';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        \Livewire\Livewire::forceAssetInjection();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());

        Blade::if('admin', function () {
            return auth()->user()?->is_admin;
        });
    }
}
