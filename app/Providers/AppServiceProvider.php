<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
    public function boot()
{
    // Share $allTemples with the layout or any view that needs it
    view()->composer('*', function ($view) {
    $allTemples = \App\Models\Temple::orderBy('name')->get();
    $view->with('allTemples', $allTemples);
});

}
}
