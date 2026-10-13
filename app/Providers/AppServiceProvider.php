<?php

namespace App\Providers;

use App\Http\Controllers\DashboardController;
use App\Support\PublicContentCache;
use App\Support\SchemaCache;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // One instance per request, so schema lookups are memoised in memory
        // for the rest of the request.
        $this->app->scoped(SchemaCache::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(MigrationsEnded::class, fn () => SchemaCache::flush());

        // Matches the hint shown on the reset/set-password forms.
        Password::defaults(fn () => Password::min(8)->max(255)->letters()->numbers());

        // A multi-step registration wizard naturally takes more than a
        // handful of submit attempts while fixing validation errors
        // (city, mobile format, etc). The old project's "auth" limiter
        // allows 10/min keyed by email+ip for exactly this reason, rather
        // than the framework default of 5/min by ip alone.
        RateLimiter::for('registration', function ($request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        View::composer([
            'layouts.partials.footer',
            'pages.business-listing',
            'pages.startup-listing',
            'pages.investor-listing',
            'pages.mentor-listing',
        ], function ($view): void {
            // Reference data maintained outside this app; cached for an hour
            // (see PublicContentCache) instead of queried on every page.
            $view->with(PublicContentCache::layout(function (): array {
                $categories = collect();

                if (SchemaCache::hasTable('industry_categories')) {
                    $categories = DB::table('industry_categories')
                        ->where('category_status', 1)
                        ->orderBy('cat_id')
                        ->get(['cat_id', 'category_name', 'parent_id']);
                }

                $ukCities = collect();
                if (SchemaCache::hasTable('bx_cities')) {
                    $ukCities = DB::table('bx_cities')
                        ->where('country', 5)
                        ->orderBy('city')
                        ->get(['id', 'city'])
                        ->map(fn ($city): array => [
                            'id' => $city->id,
                            'name' => $city->city,
                        ]);
                }

                return [
                    'industryCategoryTree' => $categories
                        ->where('parent_id', 0)
                        ->map(fn ($parent): array => [
                            'id' => $parent->cat_id,
                            'name' => $parent->category_name,
                            'children' => $categories
                                ->where('parent_id', $parent->cat_id)
                                ->map(fn ($child): array => [
                                    'id' => $child->cat_id,
                                    'name' => $child->category_name,
                                ])
                                ->values()
                                ->all(),
                        ])
                        ->values()
                        ->all(),
                    'ukCities' => $ukCities,
                ];
            }));
        });

        // Every dashboard page shares the same header (search, notifications,
        // account menu) via this one partial — keep the data it needs in one
        // place instead of repeating ->with($this->dashboardActivityData())
        // in each DashboardController action.
        View::composer('dashboard.partials.header', function ($view): void {
            if (Auth::check()) {
                $view->with(app(DashboardController::class)->dashboardActivityData());
            }
        });
    }
}
