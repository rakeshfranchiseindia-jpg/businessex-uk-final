<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Caches public, visitor-independent page data.
 *
 * Profiles are written by this application, so every profile write calls
 * forgetProfileContent() and the change shows on the next request. Articles,
 * testimonials, categories and cities are maintained outside this app, so
 * they rely on the TTL: at most HOME_TTL / LAYOUT_TTL seconds stale, or run
 * `php artisan cache:clear` to publish immediately.
 */
class PublicContentCache
{
    public const HOME_KEY = 'public:home:v1';

    private const LAYOUT_KEY = 'public:layout:v1';

    private const HOME_TTL = 300;

    private const LAYOUT_TTL = 3600;

    /**
     * @param  Closure(): array<string, mixed>  $build
     * @return array<string, mixed>
     */
    public static function home(Closure $build): array
    {
        return Cache::remember(self::HOME_KEY, self::HOME_TTL, $build);
    }

    /**
     * Footer/listing reference data (industry tree, UK cities).
     *
     * @param  Closure(): array<string, mixed>  $build
     * @return array<string, mixed>
     */
    public static function layout(Closure $build): array
    {
        return Cache::remember(self::LAYOUT_KEY, self::LAYOUT_TTL, $build);
    }

    public static function forgetProfileContent(): void
    {
        Cache::forget(self::HOME_KEY);
    }
}
