<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        $industryCategoryTree = null;

        View::composer([
            'layouts.partials.footer',
            'pages.business-listing',
            'pages.startup-listing',
            'pages.investor-listing',
            'pages.mentor-listing',
        ], function ($view) use (&$industryCategoryTree): void {
            if ($industryCategoryTree === null) {
                $categories = collect();

                if (Schema::hasTable('industry_categories')) {
                    $categories = DB::table('industry_categories')
                        ->where('category_status', 1)
                        ->orderBy('cat_id')
                        ->get(['cat_id', 'category_name', 'parent_id']);
                }

                $industryCategoryTree = $categories
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
                    ->all();
            }

            $view->with('industryCategoryTree', $industryCategoryTree);

            $ukCities = collect();
            if (Schema::hasTable('bx_cities')) {
                $ukCities = DB::table('bx_cities')
                    ->where('country', 5)
                    ->orderBy('city')
                    ->get(['id', 'city'])
                    ->map(fn ($city): array => [
                        'id' => $city->id,
                        'name' => $city->city,
                    ]);
            }

            $view->with('ukCities', $ukCities);
        });
    }
}
