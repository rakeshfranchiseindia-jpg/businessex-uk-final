<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ListingControllersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('industry_categories', function (Blueprint $table): void {
            $table->increments('cat_id');
            $table->string('category_name');
            $table->integer('parent_id')->default(0);
            $table->tinyInteger('category_status')->default(1);
            $table->timestamps();
        });
        Schema::create('bx_cities', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('country');
            $table->string('city');
        });
        DB::table('industry_categories')->insert([
            ['cat_id' => 1, 'category_name' => 'Technology', 'parent_id' => 0, 'category_status' => 1],
            ['cat_id' => 2, 'category_name' => 'Software', 'parent_id' => 1, 'category_status' => 1],
            ['cat_id' => 3, 'category_name' => 'Healthcare', 'parent_id' => 0, 'category_status' => 1],
        ]);
        DB::table('bx_cities')->insert(['country' => 5, 'city' => 'London']);

        Schema::create('profile_business', function (Blueprint $table): void {
            $table->increments('business_id');
            $table->tinyInteger('business_profile_status');
            $table->timestamp('created_at')->nullable();
            $table->string('advmt_headline')->nullable();
            $table->string('seller_company')->nullable();
            $table->string('seller_name')->nullable();
            $table->string('seller_intro')->nullable();
            $table->string('industry_sector')->nullable();
            $table->text('company_summary')->nullable();
            $table->string('business_pitch')->nullable();
            $table->string('ofc_city')->nullable();
            $table->string('ofc_state')->nullable();
            $table->string('ofc_country')->nullable();
            $table->decimal('annual_sales', 16, 2)->default(0);
            $table->decimal('inv_asking_price', 16, 2)->nullable();
            $table->integer('estb_year')->nullable();
            $table->string('emp_count')->nullable();
            $table->tinyInteger('seeking_buyers')->nullable();
            $table->integer('seeking_investors')->nullable();
            $table->tinyInteger('seeking_loan')->nullable();
            $table->string('seller_prof_pic')->nullable();
        });
        Schema::create('ind_pref_business', function (Blueprint $table): void {
            $table->increments('business_ind_pref_id');
            $table->integer('business_profile_id');
            $table->tinyInteger('profile_status');
            $table->integer('parent_category_id');
            $table->integer('sub_category_id');
        });
        Schema::create('profile_investor', function (Blueprint $table): void {
            $table->increments('investor_id');
            $table->tinyInteger('inv_profile_status');
            $table->timestamp('created_at')->nullable();
            $table->string('inv_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_designation')->nullable();
            $table->string('inv_city')->nullable();
            $table->string('company_city')->nullable();
            $table->string('inv_headline')->nullable();
            $table->string('inv_intro')->nullable();
            $table->string('inv_abt_urself')->nullable();
            $table->text('company_summary')->nullable();
            $table->string('sector_preference')->nullable();
            $table->string('location_preference')->nullable();
            $table->decimal('invest_size_min', 16, 2)->default(0);
            $table->decimal('invest_size_max', 16, 2)->default(0);
            $table->string('inv_profile_pic_path')->nullable();
            $table->string('company_logo_path')->nullable();
        });
        Schema::create('profile_startups', function (Blueprint $table): void {
            $table->increments('startup_id');
            $table->tinyInteger('startup_profile_status');
            $table->timestamp('created_at')->nullable();
            $table->string('startup_name');
            $table->string('advmt_headline')->nullable();
            $table->string('name_of_entity')->nullable();
            $table->integer('industry_sector')->nullable();
            $table->string('inv_asking_price')->nullable();
            $table->string('ofc_city')->nullable();
            $table->string('ofc_state')->nullable();
            $table->string('ofc_country')->nullable();
            $table->string('startup_prof_pic')->nullable();
            $table->text('company_summary')->nullable();
            $table->string('startup_intro')->nullable();
            $table->string('business_pitch')->nullable();
            $table->string('company_stage')->nullable();
            $table->integer('seeking_investors')->nullable();
            $table->tinyInteger('seeking_mentorship')->nullable();
            $table->tinyInteger('seeking_loan')->nullable();
            $table->tinyInteger('seeking_acquirers')->nullable();
            $table->tinyInteger('seeking_incubators')->nullable();
            $table->string('buyer_sell_price')->nullable();
        });
        Schema::create('profile_mentors', function (Blueprint $table): void {
            $table->increments('mentor_id');
            $table->tinyInteger('mentor_profile_status');
            $table->timestamp('created_at')->nullable();
            $table->string('mentor_name');
            $table->string('mentor_company')->nullable();
            $table->string('mentor_designation')->nullable();
            $table->string('mentor_city')->nullable();
            $table->string('mentor_location')->nullable();
            $table->string('mentor_country')->nullable();
            $table->string('mentor_adv_headline')->nullable();
            $table->string('mentor_intro')->nullable();
            $table->text('mentor_profile_summary')->nullable();
            $table->string('mentor_profile_pic')->nullable();
            $table->softDeletes();
        });

        $this->seedOneRecordPerListingType();
    }

    protected function tearDown(): void
    {
        foreach ([
            'profile_mentors',
            'profile_startups',
            'profile_investor',
            'ind_pref_business',
            'profile_business',
            'bx_cities',
            'industry_categories',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        parent::tearDown();
    }

    public function test_each_listing_page_uses_active_database_records(): void
    {
        $this->get(route('business-listing'))->assertOk()->assertSee('Climate Business');
        $this->get(route('investor-listing'))->assertOk()->assertSee('London Capital');
        $this->get(route('startup-listing'))->assertOk()->assertSee('Climate Startup');
        $this->get(route('mentor-listing'))->assertOk()->assertSee('Jane Mentor');
    }

    public function test_business_listing_filters_by_keyword_city_and_industry(): void
    {
        $this->get(route('business-listing', [
            'q' => 'Climate',
            'city' => 'London',
            'industry' => 2,
        ]))
            ->assertOk()
            ->assertSee('Climate Business')
            ->assertDontSee('Inactive Business');
    }

    public function test_business_listing_filters_by_investment_button_range_without_including_upper_bound(): void
    {
        DB::table('profile_business')->insert([
            [
                'business_profile_status' => 1,
                'created_at' => now(),
                'advmt_headline' => 'Inside Investment Range',
                'inv_asking_price' => 250000,
            ],
            [
                'business_profile_status' => 1,
                'created_at' => now(),
                'advmt_headline' => 'Upper Investment Boundary',
                'inv_asking_price' => 500000,
            ],
        ]);

        $this->get(route('business-listing', [
            'investment_min' => 200000,
            'investment_max' => 500000,
            'investment_max_exclusive' => 1,
        ]))
            ->assertOk()
            ->assertSee('Inside Investment Range')
            ->assertSee('Climate Business')
            ->assertDontSee('Upper Investment Boundary');
    }

    public function test_investor_listing_filters_by_company_type_location_industry_and_range(): void
    {
        $this->get(route('investor-listing', [
            'intent' => 'firm',
            'city' => 'London',
            'industry' => 2,
            'min_amount' => 100000,
            'max_amount' => 250000,
        ]))
            ->assertOk()
            ->assertSee('London Capital');
    }

    public function test_startup_listing_filters_by_industry_city_and_funding_range(): void
    {
        $this->get(route('startup-listing', [
            'city' => 'London',
            'industry' => 2,
            'min_amount' => 200000,
            'max_amount' => 300000,
        ]))
            ->assertOk()
            ->assertSee('Climate Startup');
    }

    public function test_mentor_listing_filters_by_keyword_and_city(): void
    {
        $this->get(route('mentor-listing', ['q' => 'Climate', 'city' => 'London']))
            ->assertOk()
            ->assertSee('Jane Mentor');
    }

    public function test_listing_pagination_preserves_filters_and_reports_result_counts(): void
    {
        for ($id = 2; $id <= 13; $id++) {
            DB::table('profile_business')->insert([
                'business_profile_status' => 1,
                'created_at' => now()->subDays($id),
                'advmt_headline' => 'Extra Business ' . $id,
                'seller_company' => 'Company ' . $id,
                'seller_name' => 'Seller ' . $id,
                'ofc_city' => 'London',
                'annual_sales' => 1000,
            ]);
        }

        $this->get(route('business-listing', ['city' => 'London', 'page' => 2]))
            ->assertOk()
            ->assertSee('Showing')
            ->assertSee('of <strong>13</strong>', false)
            ->assertSee('city=London&amp;page=1', false);
    }

    private function seedOneRecordPerListingType(): void
    {
        DB::table('profile_business')->insert([
            'business_profile_status' => 1,
            'created_at' => now(),
            'advmt_headline' => 'Climate Business',
            'seller_company' => 'Climate Business Ltd',
            'seller_name' => 'Business Seller',
            'seller_intro' => 'Growing climate services company',
            'industry_sector' => 'Software',
            'company_summary' => 'Climate solutions for UK companies',
            'ofc_city' => 'London',
            'ofc_country' => 'United Kingdom',
            'annual_sales' => 100000,
            'inv_asking_price' => 200000,
            'estb_year' => 2018,
            'emp_count' => '10',
            'seeking_investors' => 1,
        ]);
        DB::table('profile_business')->insert([
            'business_profile_status' => 0,
            'created_at' => now(),
            'advmt_headline' => 'Inactive Business',
            'seller_name' => 'Hidden Seller',
            'ofc_city' => 'London',
            'ofc_country' => 'United Kingdom',
        ]);
        DB::table('ind_pref_business')->insert([
            'business_profile_id' => 1,
            'profile_status' => 1,
            'parent_category_id' => 1,
            'sub_category_id' => 2,
        ]);
        DB::table('profile_investor')->insert([
            'inv_profile_status' => 1,
            'created_at' => now(),
            'inv_name' => 'London Capital',
            'company_name' => 'London Capital Partners',
            'company_designation' => 'Partner',
            'inv_city' => 'London',
            'inv_headline' => 'Climate investment',
            'inv_intro' => 'Climate investor',
            'company_summary' => 'Supporting climate companies',
            'sector_preference' => 'Software',
            'location_preference' => 'London',
            'invest_size_min' => 100000,
            'invest_size_max' => 250000,
        ]);
        DB::table('profile_startups')->insert([
            'startup_profile_status' => 1,
            'created_at' => now(),
            'startup_name' => 'Founder',
            'advmt_headline' => 'Climate Startup',
            'name_of_entity' => 'Climate Startup Ltd',
            'industry_sector' => 2,
            'inv_asking_price' => '250000',
            'ofc_city' => 'London',
            'company_summary' => 'Climate technology platform',
            'company_stage' => 'Growth',
        ]);
        DB::table('profile_mentors')->insert([
            'mentor_profile_status' => 1,
            'created_at' => now(),
            'mentor_name' => 'Jane Mentor',
            'mentor_company' => 'Climate Advisory',
            'mentor_designation' => 'Climate Adviser',
            'mentor_city' => 'London',
            'mentor_intro' => 'Climate business mentor',
            'mentor_profile_summary' => 'Experienced climate founder and adviser',
        ]);
    }
}
