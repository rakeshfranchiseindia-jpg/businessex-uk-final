<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomeBusinessOpportunitiesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('profile_business', function (Blueprint $table): void {
            $table->increments('business_id');
            $table->string('business_profile_str');
            $table->tinyInteger('business_profile_status');
            $table->timestamp('created_at')->nullable();
            $table->string('advmt_headline')->nullable();
            $table->string('seller_company')->nullable();
            $table->string('industry_sector')->nullable();
            $table->decimal('inv_asking_price', 16, 4)->default(0);
            $table->string('ofc_city')->nullable();
            $table->string('seller_prof_pic')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('profile_business');

        parent::tearDown();
    }

    public function test_homepage_shows_the_twelve_latest_active_business_opportunities_in_the_slider(): void
    {
        for ($id = 1; $id <= 15; $id++) {
            DB::table('profile_business')->insert([
                'business_profile_str' => 'HOME-BUSINESS-' . $id,
                'business_profile_status' => $id === 15 ? 0 : 1,
                'created_at' => now()->subDays(15 - $id),
                'advmt_headline' => 'Homepage Opportunity ' . str_pad((string) $id, 2, '0', STR_PAD_LEFT),
                'seller_company' => 'Opportunity Company ' . $id,
                'industry_sector' => 'Manufacturing',
                'inv_asking_price' => 250000 + ($id * 10000),
                'ofc_city' => 'London',
                'seller_prof_pic' => null,
            ]);
        }

        $response = $this->get(route('home'))
            ->assertOk()
            ->assertSee('Business For Sale Opportunities')
            ->assertSee('14 active business opportunities')
            ->assertSee('Homepage Opportunity 14')
            ->assertSee('&#163; 390,000', false)
            ->assertSee('Manufacturing')
            ->assertSee('London')
            ->assertSee('£200,000 - under £500,000')
            ->assertSee('(14)')
            ->assertSee('investment_min=200000')
            ->assertSee('investment_max=500000')
            ->assertSee(asset('assets/img/default-business-profile.png'));

        for ($id = 3; $id <= 14; $id++) {
            $response->assertSee('Homepage Opportunity ' . str_pad((string) $id, 2, '0', STR_PAD_LEFT));
        }

        $response->assertDontSee('Homepage Opportunity 01')
            ->assertDontSee('Homepage Opportunity 02')
            ->assertDontSee('Homepage Opportunity 15');

        $this->assertSame(12, substr_count($response->getContent(), 'data-business-opportunity'));
    }
}
