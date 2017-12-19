<?php

namespace App\Providers;

use Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        view()->composer('*', function ($view) {
            $loggedUser = $lShop = null;
            if (Auth::check()) {
                $loggedUser = Auth::user();
                $lShop = $loggedUser->shop;
            }

            $view->with(['lUser' => $loggedUser, 'lShop' => $lShop]);
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
