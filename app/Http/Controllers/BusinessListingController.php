<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class BusinessListingController extends AbstractListingController
{
    public function index(Request $request): View
    {
        $this->validateFilters($request, ['sale', 'investor', 'loan']);
        $query = DB::table('profile_business')
            ->where('business_profile_status', 1);

        $this->applySearch($query, $request, [
            'advmt_headline',
            'seller_company',
            'seller_name',
            'seller_intro',
            'industry_sector',
            'company_summary',
            'business_pitch',
            'ofc_city',
        ]);
        $this->applyLocation($query, $request, ['ofc_city', 'ofc_state', 'ofc_country']);
        $this->applyBusinessIndustry($query, $request);
        $this->applyAmountRange($query, $request, 'annual_sales');
        $this->applyAmountRange(
            $query,
            $request,
            'inv_asking_price',
            null,
            'investment_min',
            'investment_max',
            'investment_max_exclusive'
        );
        $this->applyIntent($query, $request);
        $this->applyMembershipPriority($query, 'profile_business');
        $this->applySort($query, $request, 'profile_business', 'business_id', 'advmt_headline', 'annual_sales');

        $listings = $query->paginate(12)->withQueryString();
        $listings->through(fn ($business): object => $this->card(
            (int) $business->business_id,
            $business->advmt_headline ?? $business->seller_company ?? $business->seller_name ?? '',
            $business->ofc_city ?? null,
            $business->company_summary ?? $business->seller_intro ?? $business->business_pitch ?? null,
            $business->industry_sector ?? null,
            isset($business->inv_asking_price)
                ? '£' . number_format((float) $business->inv_asking_price)
                : null,
            $business->seller_prof_pic ?? null,
            'assets/img/default-business-profile.png',
            array_filter([
                'Established' => $business->estb_year ?? null,
                'Employees' => $business->emp_count ?? null,
                'Seeking' => isset($business->inv_asking_price)
                    ? '£' . number_format((float) $business->inv_asking_price)
                    : null,
            ])
        ));

        return view('pages.business-listing', [
            'page' => 'listing',
            'showHeader' => true,
            'showFooter' => true,
            'listings' => $listings,
            'listingType' => 'business',
            'listingLabel' => 'businesses',
        ]);
    }

    private function applyBusinessIndustry(Builder $query, Request $request): void
    {
        [$categoryIds, $parentIds, $categoryNames] = $this->industrySelections($request);
        if ($categoryIds === [] && $parentIds === [] && $categoryNames === []) {
            return;
        }

        $query->where(function (Builder $nested) use ($categoryIds, $parentIds, $categoryNames): void {
            foreach ($categoryNames as $name) {
                $nested->orWhere('industry_sector', 'like', '%' . $name . '%');
            }

            if (Schema::hasTable('ind_pref_business')
                && Schema::hasColumns('ind_pref_business', [
                    'business_profile_id',
                    'profile_status',
                    'parent_category_id',
                    'sub_category_id',
                ])) {
                $nested->orWhereExists(function (Builder $preference) use ($categoryIds, $parentIds): void {
                    $preference->selectRaw('1')
                        ->from('ind_pref_business as business_industry')
                        ->whereColumn('business_industry.business_profile_id', 'profile_business.business_id')
                        ->where('business_industry.profile_status', 1)
                        ->where(function (Builder $categories) use ($categoryIds, $parentIds): void {
                            if ($categoryIds !== []) {
                                $categories->whereIn('business_industry.sub_category_id', $categoryIds);
                            }
                            if ($parentIds !== []) {
                                $method = $categoryIds === [] ? 'whereIn' : 'orWhereIn';
                                $categories->{$method}('business_industry.parent_category_id', $parentIds);
                            }
                        });
                });
            }
        });
    }

    private function applyIntent(Builder $query, Request $request): void
    {
        $intent = $request->query('intent');
        $columns = [
            'sale' => ['seeking_buyers', 'buyer_sell_price'],
            'investor' => ['seeking_investors'],
            'loan' => ['seeking_loan'],
        ];
        if (!isset($columns[$intent])) {
            return;
        }

        $query->where(function (Builder $nested) use ($columns, $intent): void {
            foreach ($columns[$intent] as $column) {
                if (Schema::hasColumn('profile_business', $column)) {
                    if ($column === 'buyer_sell_price') {
                        $nested->orWhere($column, '>', 0);
                    } else {
                        $nested->orWhere($column, '>', 0);
                    }
                }
            }
        });
    }
}
