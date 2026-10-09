<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly TestimonialController $testimonialController
    ) {
    }

    public function index(): View
    {
        $industryCategories = collect();
        $ukCities = collect();
        $investmentRanges = [
            ['min' => 500, 'max' => 2000, 'max_exclusive' => true, 'label' => '£500 - under £2,000'],
            ['min' => 2000, 'max' => 5000, 'max_exclusive' => true, 'label' => '£2,000 - under £5,000'],
            ['min' => 5000, 'max' => 10000, 'max_exclusive' => true, 'label' => '£5,000 - under £10,000'],
            ['min' => 10000, 'max' => 20000, 'max_exclusive' => true, 'label' => '£10,000 - under £20,000'],
            ['min' => 20000, 'max' => 30000, 'max_exclusive' => true, 'label' => '£20,000 - under £30,000'],
            ['min' => 30000, 'max' => 50000, 'max_exclusive' => true, 'label' => '£30,000 - under £50,000'],
            ['min' => 50000, 'max' => 100000, 'max_exclusive' => true, 'label' => '£50,000 - under £100,000'],
            ['min' => 100000, 'max' => 200000, 'max_exclusive' => true, 'label' => '£100,000 - under £200,000'],
            ['min' => 200000, 'max' => 500000, 'max_exclusive' => true, 'label' => '£200,000 - under £500,000'],
            ['min' => 500000, 'max' => 1000000, 'max_exclusive' => true, 'label' => '£500,000 - under £1,000,000'],
            ['min' => 1000000, 'max' => 2000000, 'max_exclusive' => false, 'label' => '£1,000,000 - £2,000,000'],
        ];
        $latestArticles = collect();
        $businessOpportunities = collect();
        $businessOpportunityCount = 0;
        $featuredInvestors = collect();
        $verifiedInvestorCount = 0;
        $highGrowthStartups = collect();
        $activeStartupCount = 0;
        $worldClassMentors = collect();
        $activeMentorCount = 0;

        if (Schema::hasTable('industry_categories')
            && Schema::hasTable('ind_pref_business')
            && Schema::hasTable('profile_business')) {
            $opportunityCounts = DB::table('ind_pref_business as industry')
                ->join('profile_business as business', 'business.business_id', '=', 'industry.business_profile_id')
                ->where('industry.profile_status', 1)
                ->where('business.business_profile_status', 1)
                ->select('industry.parent_category_id')
                ->selectRaw('COUNT(DISTINCT business.business_id) as opportunity_count')
                ->groupBy('industry.parent_category_id')
                ->pluck('opportunity_count', 'parent_category_id');

            $industryCategories = DB::table('industry_categories')
                ->where('category_status', 1)
                ->where('parent_id', 0)
                ->orderBy('cat_id')
                ->get(['cat_id', 'category_name'])
                ->map(fn ($category): object => (object) [
                    'id' => $category->cat_id,
                    'name' => $category->category_name,
                    'count' => (int) ($opportunityCounts[$category->cat_id] ?? 0),
                ]);
        }

        if (Schema::hasTable('bx_cities') && Schema::hasTable('profile_business')) {
            $cityCounts = DB::table('profile_business')
                ->where('business_profile_status', 1)
                ->whereRaw('LOWER(TRIM(ofc_country)) = ?', ['united kingdom'])
                ->whereNotNull('ofc_city')
                ->selectRaw('LOWER(TRIM(ofc_city)) as city_key, COUNT(DISTINCT business_id) as opportunity_count')
                ->groupByRaw('LOWER(TRIM(ofc_city))')
                ->pluck('opportunity_count', 'city_key');

            $ukCities = DB::table('bx_cities')
                ->where('country', 5)
                ->orderBy('city')
                ->get(['id', 'city'])
                ->map(fn ($city): object => (object) [
                    'id' => $city->id,
                    'name' => $city->city,
                    'count' => (int) ($cityCounts[mb_strtolower(trim($city->city))] ?? 0),
                ]);
        }

        if (Schema::hasTable('profile_business')
            && Schema::hasColumns('profile_business', ['business_profile_status', 'inv_asking_price'])) {
            $rangeExpressions = [];
            $rangeBindings = [];

            foreach ($investmentRanges as $index => $range) {
                $upperOperator = $range['max_exclusive'] ? '<' : '<=';
                $rangeExpressions[] = "SUM(CASE WHEN CAST(inv_asking_price AS DECIMAL(20, 4)) >= ? AND CAST(inv_asking_price AS DECIMAL(20, 4)) {$upperOperator} ? THEN 1 ELSE 0 END) AS investment_range_{$index}";
                $rangeBindings[] = $range['min'];
                $rangeBindings[] = $range['max'];
            }

            $counts = DB::table('profile_business')
                ->where('business_profile_status', 1)
                ->selectRaw(implode(', ', $rangeExpressions), $rangeBindings)
                ->first();

            foreach ($investmentRanges as $index => &$range) {
                $range['count'] = (int) ($counts->{'investment_range_' . $index} ?? 0);
            }
            unset($range);
        }

        if (Schema::hasTable('bx_articles')) {
            $articleQuery = DB::table('bx_articles')
                ->where('bx_articles.article_status', 1)
                ->select([
                    'bx_articles.article_id',
                    'bx_articles.article_title',
                    'bx_articles.short_desc',
                    'bx_articles.article_content',
                    'bx_articles.image_path',
                    'bx_articles.listing_image_path',
                    'bx_articles.created_at',
                    'bx_articles.updated_at',
                ]);

            if (Schema::hasTable('bx_author')) {
                $articleQuery
                    ->leftJoin('bx_author', 'bx_author.author_id', '=', 'bx_articles.author_id')
                    ->addSelect('bx_author.author_name');
            }

            $latestArticles = $articleQuery
                ->orderByRaw('COALESCE(bx_articles.created_at, bx_articles.updated_at) DESC')
                ->orderByDesc('bx_articles.article_id')
                ->limit(4)
                ->get()
                ->map(function ($article): object {
                    $imagePath = $article->listing_image_path ?: $article->image_path;
                    $article->image_url = $this->articleImageUrl($imagePath, (int) $article->article_id);
                    $article->author_name = $article->author_name ?? 'BusinessX Editorial';
                    $publishedAt = $article->created_at ?: $article->updated_at;
                    $article->published_at_label = $publishedAt
                        ? CarbonImmutable::parse($publishedAt)->format('d M Y')
                        : 'Recently';

                    return $article;
                });
        }

        if (Schema::hasTable('profile_business') && Schema::hasColumns('profile_business', [
            'business_id',
            'business_profile_status',
            'created_at',
            'advmt_headline',
            'seller_company',
            'industry_sector',
            'inv_asking_price',
            'ofc_city',
            'seller_prof_pic',
        ])) {
            $businessQuery = DB::table('profile_business')
                ->where('business_profile_status', 1);

            $businessOpportunityCount = (clone $businessQuery)->count();
            $businessOpportunities = $businessQuery
                ->orderByDesc('created_at')
                ->orderByDesc('business_id')
                ->limit(12)
                ->get([
                    'business_id',
                    'advmt_headline',
                    'seller_company',
                    'industry_sector',
                    'inv_asking_price',
                    'ofc_city',
                    'seller_prof_pic',
                ])
                ->map(function ($business): object {
                    $business->display_title = $business->advmt_headline
                        ?: ($business->seller_company ?: 'Business opportunity');
                    $business->display_industry = $business->industry_sector
                        && !is_numeric($business->industry_sector)
                            ? $business->industry_sector
                            : 'Business opportunity';
                    $business->image_url = $this->businessImageUrl($business->seller_prof_pic);

                    return $business;
                });
        }

        if (Schema::hasTable('profile_investor') && Schema::hasColumns('profile_investor', [
            'investor_id',
            'inv_profile_status',
            'created_at',
            'inv_name',
            'company_name',
            'company_designation',
            'inv_city',
            'company_city',
            'inv_headline',
            'inv_intro',
            'inv_abt_urself',
            'company_summary',
            'inv_profile_pic_path',
        ])) {
            $investorQuery = DB::table('profile_investor')
                ->where('inv_profile_status', 1);

            $verifiedInvestorCount = (clone $investorQuery)->count();
            $featuredInvestors = $investorQuery
                ->orderByDesc('created_at')
                ->orderByDesc('investor_id')
                ->limit(12)
                ->get([
                    'investor_id',
                    'inv_name',
                    'company_name',
                    'company_designation',
                    'inv_city',
                    'company_city',
                    'inv_headline',
                    'inv_intro',
                    'inv_abt_urself',
                    'company_summary',
                    'inv_profile_pic_path',
                ])
                ->map(function ($investor): object {
                    $investor->display_name = $investor->inv_name ?: 'Investor';
                    $investor->display_company = $investor->company_name ?: 'Independent Investor';
                    $investor->display_city = $investor->inv_city ?: $investor->company_city;
                    $investor->display_summary = $investor->company_summary
                        ?: ($investor->inv_abt_urself ?: ($investor->inv_intro ?: $investor->inv_headline));
                    $investor->image_url = $this->investorImageUrl($investor->inv_profile_pic_path);

                    return $investor;
                });
        }

        if (Schema::hasTable('profile_startups') && Schema::hasColumns('profile_startups', [
            'startup_id',
            'startup_profile_status',
            'created_at',
            'startup_name',
            'startup_email',
            'startup_mobile',
            'startup_designation',
            'advmt_headline',
            'name_of_entity',
            'industry_sector',
            'inv_asking_price',
            'ofc_city',
            'startup_prof_pic',
            'company_summary',
            'startup_intro',
            'business_pitch',
        ])) {
            $startupQuery = DB::table('profile_startups')
                ->where('startup_profile_status', 1);
            $startupColumns = [
                'profile_startups.startup_id',
                'profile_startups.startup_name',
                'profile_startups.startup_email',
                'profile_startups.startup_mobile',
                'profile_startups.startup_designation',
                'profile_startups.advmt_headline',
                'profile_startups.name_of_entity',
                'profile_startups.industry_sector',
                'profile_startups.inv_asking_price',
                'profile_startups.ofc_city',
                'profile_startups.startup_prof_pic',
                'profile_startups.company_summary',
                'profile_startups.startup_intro',
                'profile_startups.business_pitch',
            ];

            if (Schema::hasTable('industry_categories')) {
                $startupQuery->leftJoin(
                    'industry_categories as startup_industry',
                    'startup_industry.cat_id',
                    '=',
                    'profile_startups.industry_sector'
                );
                $startupColumns[] = 'startup_industry.category_name as industry_name';
            }

            $activeStartupCount = (clone $startupQuery)->count();
            $highGrowthStartups = $startupQuery
                ->orderByDesc('profile_startups.created_at')
                ->orderByDesc('profile_startups.startup_id')
                ->limit(12)
                ->get($startupColumns)
                ->map(function ($startup): object {
                    $startup->display_title = $startup->advmt_headline
                        ?: ($startup->name_of_entity ?: 'Startup opportunity');
                    $startup->display_industry = $startup->industry_name
                        ?: ($startup->industry_sector && !is_numeric($startup->industry_sector)
                            ? $startup->industry_sector
                            : 'Startup opportunity');
                    $startup->display_summary = $startup->company_summary
                        ?: ($startup->startup_intro ?: $startup->business_pitch);
                    $startup->image_url = $this->startupImageUrl($startup->startup_prof_pic);

                    return $startup;
                });
        }

        if (Schema::hasTable('profile_mentors') && Schema::hasColumns('profile_mentors', [
            'mentor_id',
            'mentor_profile_status',
            'created_at',
            'mentor_name',
            'mentor_company',
            'mentor_designation',
            'mentor_city',
            'mentor_adv_headline',
            'mentor_intro',
            'mentor_profile_summary',
            'mentor_profile_pic',
        ])) {
            $mentorQuery = DB::table('profile_mentors')
                ->where('mentor_profile_status', 1);

            if (Schema::hasColumn('profile_mentors', 'deleted_at')) {
                $mentorQuery->whereNull('deleted_at');
            }

            $activeMentorCount = (clone $mentorQuery)->count();
            $worldClassMentors = $mentorQuery
                ->orderByDesc('profile_mentors.created_at')
                ->orderByDesc('profile_mentors.mentor_id')
                ->limit(12)
                ->get([
                    'profile_mentors.mentor_id',
                    'profile_mentors.mentor_name',
                    'profile_mentors.mentor_company',
                    'profile_mentors.mentor_designation',
                    'profile_mentors.mentor_city',
                    'profile_mentors.mentor_adv_headline',
                    'profile_mentors.mentor_intro',
                    'profile_mentors.mentor_profile_summary',
                    'profile_mentors.mentor_profile_pic',
                ])
                ->map(function ($mentor): object {
                    $mentor->display_company = $mentor->mentor_designation
                        ?: ($mentor->mentor_company ?: 'Business Mentor');
                    $mentor->display_city = $mentor->mentor_city;
                    $mentor->display_summary = $mentor->mentor_profile_summary
                        ?: ($mentor->mentor_intro ?: $mentor->mentor_adv_headline);
                    $mentor->image_url = $this->mentorImageUrl($mentor->mentor_profile_pic);

                    return $mentor;
                });
        }

        return view('pages.home', [
            'page' => 'home',
            'showHeader' => true,
            'showFooter' => true,
            'industryCategories' => $industryCategories,
            'ukCities' => $ukCities,
            'investmentRanges' => $investmentRanges,
            'latestArticles' => $latestArticles,
            'businessOpportunities' => $businessOpportunities,
            'businessOpportunityCount' => $businessOpportunityCount,
            'featuredInvestors' => $featuredInvestors,
            'verifiedInvestorCount' => $verifiedInvestorCount,
            'highGrowthStartups' => $highGrowthStartups,
            'activeStartupCount' => $activeStartupCount,
            'worldClassMentors' => $worldClassMentors,
            'activeMentorCount' => $activeMentorCount,
            'testimonials' => $this->testimonialController->homepageTestimonials(),
        ]);
    }

    private function businessImageUrl(?string $path): string
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

        return asset('assets/img/default-business-profile.png');
    }

    private function investorImageUrl(?string $path): string
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

        return asset('assets/img/default-investor-profile.png');
    }

    private function startupImageUrl(?string $path): string
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

        return asset('assets/img/default-startup-profile.png');
    }

    private function mentorImageUrl(?string $path): string
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

        return asset('assets/img/default-mentor-profile.png');
    }

    private function articleImageUrl(?string $path, int $articleId): string
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

        return asset('assets/img/article-' . ((($articleId - 1) % 4) + 1) . '.jpg');
    }
}
