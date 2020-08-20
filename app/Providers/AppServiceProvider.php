<?php

namespace App\Providers;

use App\Helpers\Menu;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        view()->composer('admin.layout.menu', function($view) {
            $menu = config('menu.list');
            $icon = config('menu.icon');
            $make = new Menu;

            $view->with([
                'menu' => $make->make($menu, $icon)
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
