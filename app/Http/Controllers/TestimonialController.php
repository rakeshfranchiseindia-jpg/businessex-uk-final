<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TestimonialController extends Controller
{
    public function homepageTestimonials(): Collection
    {
        if (!Schema::hasTable('testimonials')) {
            return collect();
        }

        $columns = Schema::getColumnListing('testimonials');
        $query = DB::table('testimonials')
            ->select(['id', 'text', 'name', 'designation']);

        if (in_array('rating', $columns, true)) {
            $query->addSelect('rating');
        } else {
            $query->selectRaw('5 as rating');
        }

        if (in_array('image_path', $columns, true)) {
            $query->addSelect('image_path');
        } else {
            $query->selectRaw('NULL as image_path');
        }

        if (in_array('is_active', $columns, true)) {
            $query->where('is_active', 1);
        }

        if (in_array('sort_order', $columns, true)) {
            $query->orderBy('sort_order');
        }

        return $query
            ->orderByDesc('id')
            ->limit(3)
            ->get()
            ->map(function ($testimonial): object {
                $testimonial->rating = max(0, min(5, (int) $testimonial->rating));
                $testimonial->image_url = $this->imageUrl($testimonial->image_path);

                return $testimonial;
            });
    }

    private function imageUrl(?string $path): string
    {
        if ($path && preg_match('#^https?://#i', $path)) {
            return $path;
        }

        if ($path) {
            $relativePath = ltrim(str_replace('\\', '/', $path), '/');
            if (!str_contains($relativePath, '..') && is_file(public_path($relativePath))) {
                return asset($relativePath);
            }
        }

        return asset('assets/img/default-business-profile.png');
    }
}
