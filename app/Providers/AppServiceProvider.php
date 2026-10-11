<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\SiteSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
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
        // Use Bootstrap pagination views to match site theme
        Paginator::useBootstrap();

        // View Composer: inject recent blog posts into footer without raw DB query in template
        View::composer(['layouts.footer', 'partials.redesign-footer'], function ($view) {
            $view->with('footerPosts', Blog::published()->latest()->limit(3)->get());
        });

        // View Composer: share $site (SiteSetting object) with every view.
        // The model caches the result forever and busts on save, so this is cheap.
        View::composer('*', function ($view) {
            $view->with('site', SiteSetting::allAsObject());
        });
    }
}
