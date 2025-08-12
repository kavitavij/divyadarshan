<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Temple;

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
        View::composer('*', function ($view) {
            // For the main navigation dropdown
            $allTemples = Temple::orderBy('name')->get();

            // --- THE DEFINITIVE NEWS TICKER LOGIC ---
            $latestNews = collect(); // Start with an empty collection to prevent errors

            try {
                // 1. Get all temples that might have news, ordered by the most recently updated.
                $templesWithNews = Temple::whereNotNull('news')
                                    ->whereJsonLength('news', '>', 0) // Ensures 'news' is not an empty array
                                    ->latest('updated_at')
                                    ->get();

                // 2. Loop through the temples and their news items to find the ones marked for the ticker.
                $latestNews = $templesWithNews->flatMap(function ($temple) {
                    // Make sure the news data is an array before we use it.
                    $newsItems = is_array($temple->news) ? $temple->news : [];

                    // Filter the news items to get only the ones where 'show_on_ticker' is true.
                    return collect($newsItems)
                        ->where('show_on_ticker', true)
                        ->pluck('text');
                })->take(10); // 3. We'll take up to 10 of the most recent ticker items.

            } catch (\Exception $e) {
                // This 'try-catch' block prevents the site from crashing if there's a database error.
                \Log::error('Could not fetch news for the ticker: ' . $e->getMessage());
            }

            // Share the data with all views.
            $view->with('allTemples', $allTemples)
                ->with('latestNews', $latestNews);
        });
    }
}