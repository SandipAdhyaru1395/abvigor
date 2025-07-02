<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\CatalogCategory;

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
        $catalog_categories = CatalogCategory::with('children')->where('parent_id', 41)->get();
        // $catalog_categories = null;
        view()->share('catalog_categories', $catalog_categories);
    }
}
