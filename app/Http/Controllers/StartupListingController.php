<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StartupListingController extends AbstractListingController
{
    public function index(Request $request): View
    {
        $this->validateFilters($request, ['sale', 'investor', 'loan', 'mentor', 'incubator']);
        $query = DB::table('profile_startups')
            ->where('startup_profile_status', 1)
            ->select('profile_startups.*');

        if (Schema::hasTable('industry_categories')) {
            $query->leftJoin(
                'industry_categories as startup_industry',
                'startup_industry.cat_id',
                '=',
                'profile_startups.industry_sector'
            )->addSelect('startup_industry.category_name as industry_name');
        }

        $this->applySearch($query, $request, [
            'startup_name',
            'advmt_headline',
            'name_of_entity',
            'startup_intro',
            'company_summary',
            'business_pitch',
            'industry_sector',
            'ofc_city',
        ]);
        $this->applyLocation($query, $request, ['ofc_city', 'ofc_state', 'ofc_country']);
        $this->applyIndustry(
            $query,
            $request,
            ['advmt_headline', 'name_of_entity', 'company_summary'],
            'industry_sector'
        );
        $this->applyAmountRange($query, $request, 'inv_asking_price');
        $this->applyIntent($query, $request);
        $this->applySort(
            $query,
            $request,
            'profile_startups',
            'profile_startups.startup_id',
            'profile_startups.startup_name',
            'profile_startups.inv_asking_price'
        );

        $listings = $query->paginate(12)->withQueryString();
        $listings->through(fn ($startup): object => $this->card(
            (int) $startup->startup_id,
            $startup->advmt_headline ?: $startup->name_of_entity ?: $startup->startup_name,
            $startup->ofc_city,
            $startup->company_summary ?: $startup->startup_intro ?: $startup->business_pitch,
            ($startup->industry_name ?? null) ?: (is_numeric($startup->industry_sector)
                ? null
                : $startup->industry_sector),
            $startup->inv_asking_price !== null
                ? '£' . number_format((float) $startup->inv_asking_price)
                : null,
            $startup->startup_prof_pic,
            'assets/img/default-startup-profile.png',
            array_filter([
                'Founder' => $startup->startup_name,
                'Company Stage' => $startup->company_stage,
                'Funding sought' => $startup->inv_asking_price !== null
                    ? '£' . number_format((float) $startup->inv_asking_price)
                    : null,
            ])
        ));

        return view('pages.startup-listing', [
            'page' => 'listing',
            'showHeader' => true,
            'showFooter' => true,
            'listings' => $listings,
            'listingType' => 'startup',
            'listingLabel' => 'startups',
        ]);
    }

    private function applyIntent(Builder $query, Request $request): void
    {
        $intentColumns = [
            'sale' => ['seeking_acquirers', 'buyer_sell_price'],
            'investor' => ['seeking_investors'],
            'loan' => ['seeking_loan'],
            'mentor' => ['seeking_mentorship'],
            'incubator' => ['seeking_incubators'],
        ];
        $intent = $request->query('intent');
        if (!isset($intentColumns[$intent])) {
            return;
        }

        $query->where(function (Builder $nested) use ($intentColumns, $intent): void {
            foreach ($intentColumns[$intent] as $column) {
                $nested->orWhere($column, '>', 0);
            }
        });
    }
}
