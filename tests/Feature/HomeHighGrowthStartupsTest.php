<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomeHighGrowthStartupsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('profile_startups', function (Blueprint $table): void {
            $table->increments('startup_id');
            $table->tinyInteger('startup_profile_status');
            $table->timestamp('created_at')->nullable();
            $table->string('startup_name');
            $table->string('startup_email')->nullable();
            $table->string('startup_mobile')->nullable();
            $table->string('startup_designation')->nullable();
            $table->string('advmt_headline')->nullable();
            $table->string('name_of_entity')->nullable();
            $table->integer('industry_sector')->nullable();
            $table->string('inv_asking_price')->nullable();
            $table->string('ofc_city')->nullable();
            $table->string('startup_prof_pic')->nullable();
            $table->text('company_summary')->nullable();
            $table->text('startup_intro')->nullable();
            $table->string('business_pitch')->nullable();
        });

        Schema::create('industry_categories', function (Blueprint $table): void {
            $table->increments('cat_id');
            $table->string('category_name');
            $table->timestamps();
        });

        DB::table('industry_categories')->insert([
            'cat_id' => 3,
            'category_name' => 'Technology',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('industry_categories');
        Schema::dropIfExists('profile_startups');

        parent::tearDown();
    }

    public function test_homepage_shows_the_twelve_latest_active_startups_in_the_slider(): void
    {
        for ($id = 1; $id <= 15; $id++) {
            DB::table('profile_startups')->insert([
                'startup_profile_status' => $id === 15 ? 0 : 1,
                'created_at' => now()->subDays(15 - $id),
                'startup_name' => 'Startup Founder ' . $id,
                'startup_email' => 'founder' . $id . '@example.test',
                'startup_mobile' => '07700900000',
                'startup_designation' => 'Founder',
                'advmt_headline' => 'Homepage Startup ' . str_pad((string) $id, 2, '0', STR_PAD_LEFT),
                'name_of_entity' => 'Startup Company ' . $id,
                'industry_sector' => 3,
                'inv_asking_price' => 150000 + ($id * 10000),
                'ofc_city' => 'London',
                'startup_prof_pic' => null,
                'company_summary' => 'Summary for startup ' . $id,
                'startup_intro' => null,
                'business_pitch' => null,
            ]);
        }

        $response = $this->get(route('home'))
            ->assertOk()
            ->assertSee('High Growth Potential Startups')
            ->assertSee('14 active startups')
            ->assertSee('Homepage Startup 14')
            ->assertSee('Technology')
            ->assertSee('&#163; 290,000', false)
            ->assertSee('London')
            ->assertSee(asset('assets/img/default-startup-profile.png'));

        for ($id = 3; $id <= 14; $id++) {
            $response->assertSee('Homepage Startup ' . str_pad((string) $id, 2, '0', STR_PAD_LEFT));
        }

        $response->assertDontSee('Homepage Startup 01')
            ->assertDontSee('Homepage Startup 02')
            ->assertDontSee('Homepage Startup 15');

        $this->assertSame(12, substr_count($response->getContent(), 'data-high-growth-startup'));
    }
}
