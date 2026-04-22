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
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::share('menuCategories', \App\Models\Category::whereNull('parent_id')
            ->where('is_active', true)
            ->where('show_in_menu', true)
            ->with(['children' => function($query) {
                $query->where('is_active', true)->with(['children' => function($q) {
                    $q->where('is_active', true);
                }]);
            }])
            ->orderBy('sort_order')
            ->get());

        // Share settings globally
        if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            $siteSettings = \App\Models\Setting::all()->pluck('value', 'key');
            \Illuminate\Support\Facades\View::share('siteSettings', $siteSettings);
        }
    }
}
