<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvestorListingController extends AbstractListingController
{
    public function index(Request $request): View
    {
        $this->validateFilters($request, ['firm', 'individual']);
        $query = DB::table('profile_investor')
            ->where('inv_profile_status', 1);

        $this->applySearch($query, $request, [
            'inv_name',
            'company_name',
            'company_designation',
            'inv_headline',
            'inv_intro',
            'inv_abt_urself',
            'company_summary',
            'sector_preference',
            'location_preference',
            'inv_city',
            'company_city',
        ]);
        $this->applyLocation($query, $request, ['inv_city', 'company_city', 'location_preference']);
        $this->applyIndustry($query, $request, ['sector_preference', 'inv_headline', 'company_summary']);
        $this->applyAmountRange($query, $request, 'invest_size_min', 'invest_size_max');

        $intent = $request->query('intent');
        if ($intent === 'firm') {
            $query->whereNotNull('company_name')->where('company_name', '<>', '');
        } elseif ($intent === 'individual') {
            $query->where(function (Builder $nested): void {
                $nested->whereNull('company_name')->orWhere('company_name', '');
            });
        }

        $this->applyMembershipPriority($query, 'profile_investor');
        $this->applySort(
            $query,
            $request,
            'profile_investor',
            'investor_id',
            'inv_name',
            'invest_size_max'
        );

        $listings = $query->paginate(12)->withQueryString();
        $listings->through(fn ($investor): object => $this->card(
            (int) $investor->investor_id,
            $investor->inv_name ?: $investor->company_name,
            $investor->inv_city ?: $investor->company_city,
            $investor->company_summary ?: $investor->inv_abt_urself ?: $investor->inv_intro,
            $investor->sector_preference ?: $investor->inv_headline,
            $investor->invest_size_min || $investor->invest_size_max
                ? '£' . number_format((float) $investor->invest_size_min)
                    . ' – £' . number_format((float) $investor->invest_size_max)
                : null,
            $investor->inv_profile_pic_path ?: $investor->company_logo_path,
            'assets/img/default-investor-profile.png',
            array_filter([
                'Company' => $investor->company_name,
                'Role' => $investor->company_designation,
                'Investment Range' => $investor->invest_size_min || $investor->invest_size_max
                    ? '£' . number_format((float) $investor->invest_size_min)
                        . ' – £' . number_format((float) $investor->invest_size_max)
                    : null,
            ])
        ));

        return view('pages.investor-listing', [
            'page' => 'listing',
            'showHeader' => true,
            'showFooter' => true,
            'listings' => $listings,
            'listingType' => 'investor',
            'listingLabel' => 'investors',
        ]);
    }
}
