<?php

namespace App\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $loggedUser = $lShop = null;
            if (Auth::check()) {
                $loggedUser = Auth::user();
                $lShop = $loggedUser->shop;
            }

            $view->with(['lUser' => $loggedUser, 'lShop' => $lShop ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
