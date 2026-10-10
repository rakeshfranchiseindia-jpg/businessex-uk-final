<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

abstract class AbstractListingController extends Controller
{
    protected function validateFilters(Request $request, array $allowedIntents = []): array
    {
        $rules = [
            'q' => ['nullable', 'string', 'max:150'],
            'min_amount' => ['nullable', 'numeric', 'min:0', 'max:999999999999'],
            'max_amount' => ['nullable', 'numeric', 'min:0', 'max:999999999999'],
            'investment_min' => ['nullable', 'numeric', 'min:0', 'max:999999999999'],
            'investment_max' => ['nullable', 'numeric', 'min:0', 'max:999999999999'],
            'investment_max_exclusive' => ['nullable', 'in:0,1'],
            'sort' => ['nullable', 'in:newest,oldest,name_asc,amount_asc,amount_desc'],
        ];
        if ($allowedIntents !== []) {
            $rules['intent'] = ['nullable', 'in:' . implode(',', $allowedIntents)];
        }

        return $request->validate($rules);
    }

    protected function applySearch(Builder $query, Request $request, array $columns): void
    {
        $term = trim((string) $request->query('q', ''));
        if ($term === '') {
            return;
        }

        $query->where(function (Builder $nested) use ($columns, $term): void {
            foreach ($columns as $column) {
                if (Schema::hasColumn($this->tableName($nested), $column)) {
                    $nested->orWhere($column, 'like', '%' . $term . '%');
                }
            }
        });
    }

    protected function applyLocation(Builder $query, Request $request, array $columns): void
    {
        $cities = $this->filterValues($request, 'city');
        if ($cities === []) {
            return;
        }

        $query->where(function (Builder $nested) use ($columns, $cities): void {
            foreach ($columns as $column) {
                foreach ($cities as $city) {
                    $nested->orWhere($column, 'like', '%' . $city . '%');
                }
            }
        });
    }

    protected function applyIndustry(
        Builder $query,
        Request $request,
        array $textColumns,
        ?string $idColumn = null
    ): void {
        [$categoryIds, $parentIds, $categoryNames] = $this->industrySelections($request);
        if ($categoryIds === [] && $parentIds === [] && $categoryNames === []) {
            return;
        }

        $query->where(function (Builder $nested) use (
            $categoryIds,
            $parentIds,
            $categoryNames,
            $textColumns,
            $idColumn
        ): void {
            if ($idColumn && $categoryIds !== []) {
                $nested->whereIn($idColumn, $categoryIds);
            } elseif ($categoryIds !== [] || $parentIds !== []) {
                $nested->whereRaw('1 = 0');
            }

            foreach ($categoryNames as $name) {
                foreach ($textColumns as $column) {
                    $nested->orWhere($column, 'like', '%' . $name . '%');
                }
            }
        });
    }

    protected function applyAmountRange(
        Builder $query,
        Request $request,
        string $minimumColumn,
        ?string $maximumColumn = null,
        string $minimumParameter = 'min_amount',
        string $maximumParameter = 'max_amount',
        ?string $maximumExclusiveParameter = null
    ): void {
        $minimum = $request->query($minimumParameter);
        $maximum = $request->query($maximumParameter);
        if ($minimum !== null && $minimum !== '' && Schema::hasColumn($this->tableName($query), $minimumColumn)) {
            $column = $maximumColumn ?: $minimumColumn;
            $query->whereRaw("CAST({$column} AS DECIMAL(20, 4)) >= ?", [(float) $minimum]);
        }
        if ($maximum !== null && $maximum !== '' && Schema::hasColumn($this->tableName($query), $minimumColumn)) {
            $operator = $maximumExclusiveParameter && $request->query($maximumExclusiveParameter) === '1'
                ? '<'
                : '<=';
            $query->whereRaw("CAST({$minimumColumn} AS DECIMAL(20, 4)) {$operator} ?", [(float) $maximum]);
        }
    }

    protected function industrySelections(Request $request): array
    {
        $categoryIds = [];
        $parentIds = [];
        $categoryNames = [];

        foreach ($this->filterValues($request, 'industry') as $value) {
            if (ctype_digit($value) && (int) $value > 0) {
                $categoryIds[] = (int) $value;
            } else {
                $categoryNames[] = $value;
            }
        }
        foreach ($this->filterValues($request, 'industry_parent') as $value) {
            if (ctype_digit($value) && (int) $value > 0) {
                $parentIds[] = (int) $value;
            } else {
                $categoryNames[] = $value;
            }
        }

        if (Schema::hasTable('industry_categories')) {
            $categories = Cache::remember(
                'listing.industry-categories.v1',
                now()->addMinutes(10),
                fn () => DB::table('industry_categories')
                    ->get(['cat_id', 'category_name', 'parent_id', 'category_status'])
            );

            if ($parentIds !== []) {
                $children = $categories
                    ->whereIn('parent_id', $parentIds)
                    ->where('category_status', 1)
                    ->pluck('cat_id')
                    ->map(fn ($id): int => (int) $id)
                    ->all();
                $categoryIds = array_merge($categoryIds, $children);
                $categoryNames = array_merge(
                    $categoryNames,
                    $categories->whereIn('cat_id', $parentIds)
                        ->pluck('category_name')
                        ->all()
                );
            }

            if ($categoryIds !== []) {
                $categoryNames = array_merge(
                    $categoryNames,
                    $categories->whereIn('cat_id', $categoryIds)
                        ->pluck('category_name')
                        ->all()
                );
            }
        }

        return [
            array_values(array_unique($categoryIds)),
            array_values(array_unique($parentIds)),
            array_values(array_unique(array_filter($categoryNames))),
        ];
    }

    /**
     * Paid members sort above free ones, higher plans first. Matches the
     * legacy businessex-uk investor/mentor listing order: Platinum(3),
     * Gold(2), Premium(1), Basic(5), Free(0), anything else last.
     */
    protected function applyMembershipPriority(Builder $query, string $table): void
    {
        if (! Schema::hasColumns($table, ['membership_paid', 'membership_plan'])) {
            return;
        }

        $query->orderByDesc("{$table}.membership_paid")
            ->orderByRaw(
                "CASE {$table}.membership_plan WHEN 3 THEN 1 WHEN 2 THEN 2 WHEN 1 THEN 3 WHEN 5 THEN 4 WHEN 0 THEN 5 ELSE 6 END"
            );
    }

    protected function applySort(
        Builder $query,
        Request $request,
        string $table,
        string $idColumn,
        string $nameColumn,
        ?string $amountColumn = null
    ): void {
        $sort = $request->query('sort', 'newest');
        if ($sort === 'name_asc') {
            $query->orderBy($nameColumn)->orderByDesc($idColumn);
        } elseif ($sort === 'amount_asc' && $amountColumn) {
            $query->orderByRaw("CAST({$amountColumn} AS DECIMAL(20, 4)) ASC")->orderByDesc($idColumn);
        } elseif ($sort === 'amount_desc' && $amountColumn) {
            $query->orderByRaw("CAST({$amountColumn} AS DECIMAL(20, 4)) DESC")->orderByDesc($idColumn);
        } elseif ($sort === 'oldest') {
            if (Schema::hasColumn($table, 'created_at')) {
                $query->orderBy("{$table}.created_at");
            }
            $query->orderBy($idColumn);
        } else {
            if (Schema::hasColumn($table, 'created_at')) {
                $query->orderByDesc("{$table}.created_at");
            }
            $query->orderByDesc($idColumn);
        }
    }

    protected function renderListings(
        Request $request,
        Builder $query,
        string $view,
        string $type,
        string $label,
        array $extra = []
    ): View {
        $listings = $query->paginate(12)->withQueryString();

        return view($view, array_merge([
            'page' => 'listing',
            'showHeader' => true,
            'showFooter' => true,
            'listings' => $listings,
            'listingType' => $type,
            'listingLabel' => $label,
        ], $extra));
    }

    protected function card(
        int $id,
        string $name,
        ?string $city,
        ?string $summary,
        ?string $industry,
        ?string $amount,
        ?string $image,
        string $fallbackImage,
        array $details = []
    ): object {
        return (object) [
            'id' => $id,
            'name' => $name ?: 'BusinessX member',
            'city' => $city ?: 'Location not specified',
            'summary' => $summary ?: 'Connect with this member to learn more about their profile.',
            'industry' => $industry ?: 'BusinessX member',
            'amount' => $amount,
            'image_url' => $this->imageUrl($image, $fallbackImage),
            'initials' => Str::upper(Str::substr(collect(explode(' ', trim($name ?: 'BX')))
                ->filter()
                ->map(fn (string $part): string => Str::substr($part, 0, 1))
                ->take(2)
                ->implode(''), 0, 2)),
            'details' => $details,
        ];
    }

    protected function imageUrl(?string $path, string $fallback): string
    {
        if ($path && preg_match('#^https?://#i', $path)) {
            return $path;
        }

        if ($path) {
            $relativePath = ltrim(str_replace('\\', '/', $path), '/');
            if (!str_contains($relativePath, '..')) {
                if (is_file(public_path($relativePath))) {
                    return asset($relativePath);
                }
                if (is_file(public_path('storage/' . $relativePath))) {
                    return asset('storage/' . $relativePath);
                }
            }
        }

        return asset($fallback);
    }

    protected function filterValues(Request $request, string $key): array
    {
        $value = $request->query($key, []);
        $values = is_array($value) ? $value : explode(',', (string) $value);

        return array_values(array_filter(array_map(
            static fn ($item): string => trim((string) $item),
            $values
        ), static fn (string $item): bool => $item !== ''));
    }

    private function tableName(Builder $query): string
    {
        return (string) $query->from;
    }
}
