<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Temple;
use App\Models\LatestUpdate; 

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
        // Use a try-catch block to prevent errors if the database is not ready
        try {
            View::composer('*', function ($view) {
                // For the main navigation dropdown
                $allTemples = Temple::orderBy('name')->get();

                // For the floating news ticker
                $templesWithNews = Temple::whereNotNull('news')->whereJsonLength('news', '>', 0)->latest('updated_at')->get();
                $latestNews = $templesWithNews->flatMap(function ($temple) {
                    $newsItems = is_array($temple->news) ? $temple->news : [];
                    return collect($newsItems)->where('show_on_ticker', true)->pluck('text');
                })->take(10);

                // For the homepage "Latest Updates" scrolling panel
                $latestUpdates = LatestUpdate::where('is_active', true)->latest()->get();

                // Share all the data with all views
                $view->with('allTemples', $allTemples)
                     ->with('latestNews', $latestNews)
                     ->with('latestUpdates', $latestUpdates);
            });
        } catch (\Exception $e) {
            // If the database isn't ready, we'll just log the error and continue
            // This prevents errors during commands like `php artisan migrate`
            \Log::error("Could not share view data: " . $e->getMessage());
        }
    }
}