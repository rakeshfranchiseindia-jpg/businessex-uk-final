<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomeIndustryOpportunitiesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('industry_categories', function (Blueprint $table): void {
            $table->increments('cat_id');
            $table->string('category_name');
            $table->string('category_slug');
            $table->integer('parent_id')->default(0);
            $table->tinyInteger('category_status')->default(1);
            $table->timestamps();
        });

        Schema::create('profile_business', function (Blueprint $table): void {
            $table->increments('business_id');
            $table->tinyInteger('business_profile_status')->nullable();
            $table->string('ofc_city')->nullable();
            $table->string('ofc_country')->nullable();
        });

        Schema::create('bx_cities', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('city');
            $table->string('state');
            $table->integer('country');
            $table->timestamps();
        });

        Schema::create('ind_pref_business', function (Blueprint $table): void {
            $table->increments('business_ind_pref_id');
            $table->integer('business_profile_id');
            $table->integer('user_id');
            $table->integer('parent_category_id');
            $table->integer('sub_category_id');
            $table->tinyInteger('profile_status');
            $table->timestamps();
        });

        DB::table('industry_categories')->insert([
            $this->category(1, 'Automobile', 0),
            $this->category(2, 'Car services', 1),
            $this->category(3, 'Education', 0),
            $this->category(4, 'Training', 3),
            $this->category(5, 'Inactive Industry', 0, 0),
        ]);

        DB::table('bx_cities')->insert([
            ['id' => 1, 'city' => 'London', 'state' => 'England', 'country' => 5],
            ['id' => 2, 'city' => 'Edinburgh', 'state' => 'Scotland', 'country' => 5],
            ['id' => 3, 'city' => 'Boston', 'state' => 'Massachusetts', 'country' => 2],
        ]);

        DB::table('profile_business')->insert([
            ['business_id' => 1, 'business_profile_status' => 1, 'ofc_city' => 'London', 'ofc_country' => 'United Kingdom'],
            ['business_id' => 2, 'business_profile_status' => 1, 'ofc_city' => ' london ', 'ofc_country' => 'united kingdom'],
            ['business_id' => 3, 'business_profile_status' => 0, 'ofc_city' => 'London', 'ofc_country' => 'United Kingdom'],
        ]);

        DB::table('ind_pref_business')->insert([
            $this->mapping(1, 1, 1, 2, 1),
            $this->mapping(2, 1, 1, 2, 1),
            $this->mapping(3, 2, 1, 2, 1),
            $this->mapping(4, 3, 1, 2, 1),
            $this->mapping(5, 2, 1, 3, 0),
        ]);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ind_pref_business');
        Schema::dropIfExists('profile_business');
        Schema::dropIfExists('bx_cities');
        Schema::dropIfExists('industry_categories');

        parent::tearDown();
    }

    public function test_homepage_lists_all_active_parent_industries_with_active_opportunity_counts(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('View Opportunities By Industry')
            ->assertSee('Automobile <span class="chip-count">(2)</span>', false)
            ->assertSee('Education <span class="chip-count">(0)</span>', false)
            ->assertDontSee('Inactive Industry')
            ->assertSee(route('business-listing', ['industry_parent' => 1]), false)
            ->assertSee(route('business-listing', ['industry_parent' => 3]), false)
            ->assertSee('London <span class="chip-count">(2)</span>', false)
            ->assertSee('Edinburgh <span class="chip-count">(0)</span>', false)
            ->assertDontSee('Boston')
            ->assertSee(route('business-listing', ['city' => 'London']), false);
    }

    public function test_listing_pages_load_cities_for_the_location_filter(): void
    {
        $this->get(route('business-listing', ['city' => 'London']))
            ->assertOk()
            ->assertSee('"name":"London"', false)
            ->assertSee("params.get('city')", false);
    }

    private function category(int $id, string $name, int $parentId, int $status = 1): array
    {
        return [
            'cat_id' => $id,
            'category_name' => $name,
            'category_slug' => strtolower(str_replace(' ', '-', $name)),
            'parent_id' => $parentId,
            'category_status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function mapping(int $id, int $businessId, int $parentId, int $childId, int $status): array
    {
        return [
            'business_ind_pref_id' => $id,
            'business_profile_id' => $businessId,
            'user_id' => 1,
            'parent_category_id' => $parentId,
            'sub_category_id' => $childId,
            'profile_status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
