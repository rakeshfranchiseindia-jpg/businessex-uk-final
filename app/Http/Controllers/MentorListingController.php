<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class MentorListingController extends AbstractListingController
{
    public function index(Request $request): View
    {
        $this->validateFilters($request);
        $query = DB::table('profile_mentors')
            ->where('mentor_profile_status', 1);

        if (Schema::hasColumn('profile_mentors', 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        $this->applySearch($query, $request, [
            'mentor_name',
            'mentor_company',
            'mentor_designation',
            'mentor_adv_headline',
            'mentor_intro',
            'mentor_profile_summary',
            'mentor_city',
        ]);
        $this->applyLocation($query, $request, ['mentor_city', 'mentor_location', 'mentor_country']);
        $this->applyIndustry($query, $request, [
            'mentor_company',
            'mentor_designation',
            'mentor_adv_headline',
            'mentor_intro',
            'mentor_profile_summary',
        ]);
        $this->applyMembershipPriority($query, 'profile_mentors');
        $this->applySort($query, $request, 'profile_mentors', 'mentor_id', 'mentor_name');

        $listings = $query->paginate(12)->withQueryString();
        $listings->through(fn ($mentor): object => $this->card(
            (int) $mentor->mentor_id,
            $mentor->mentor_name,
            $mentor->mentor_city ?: $mentor->mentor_location,
            $mentor->mentor_profile_summary ?: $mentor->mentor_intro,
            $mentor->mentor_designation ?: $mentor->mentor_company ?: $mentor->mentor_adv_headline,
            null,
            $mentor->mentor_profile_pic,
            'assets/img/default-mentor-profile.png',
            array_filter([
                'Company' => $mentor->mentor_company,
                'Designation' => $mentor->mentor_designation,
            ])
        ));

        return view('pages.mentor-listing', [
            'page' => 'listing',
            'showHeader' => true,
            'showFooter' => true,
            'listings' => $listings,
            'listingType' => 'mentor',
            'listingLabel' => 'mentors',
        ]);
    }
}
