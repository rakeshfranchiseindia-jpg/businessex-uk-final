<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomeFeaturedInvestorsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

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
            $table->string('inv_profile_pic_path')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('profile_investor');

        parent::tearDown();
    }

    public function test_homepage_shows_the_twelve_latest_verified_investors(): void
    {
        for ($id = 1; $id <= 15; $id++) {
            DB::table('profile_investor')->insert([
                'inv_profile_status' => $id === 15 ? 0 : 1,
                'created_at' => now()->subDays(15 - $id),
                'inv_name' => 'Homepage Investor ' . str_pad((string) $id, 2, '0', STR_PAD_LEFT),
                'company_name' => 'Investor Company ' . $id,
                'company_designation' => 'Director',
                'inv_city' => $id === 14 ? null : 'London',
                'company_city' => $id === 14 ? 'Bristol' : null,
                'inv_headline' => 'Investor headline ' . $id,
                'inv_intro' => 'Investor introduction ' . $id,
                'inv_abt_urself' => 'Investor background ' . $id,
                'company_summary' => 'Investor summary ' . $id,
                'inv_profile_pic_path' => null,
            ]);
        }

        $response = $this->get(route('home'))
            ->assertOk()
            ->assertSee('Featured Investors')
            ->assertSee('14 verified investors')
            ->assertSee('Homepage Investor 14')
            ->assertSee('Investor Company 14')
            ->assertSee('Bristol')
            ->assertSee('Investor summary 14')
            ->assertSee(asset('assets/img/default-investor-profile.png'));

        for ($id = 3; $id <= 14; $id++) {
            $response->assertSee('Homepage Investor ' . str_pad((string) $id, 2, '0', STR_PAD_LEFT));
        }

        $response->assertDontSee('Homepage Investor 01')
            ->assertDontSee('Homepage Investor 02')
            ->assertDontSee('Homepage Investor 15');

        $this->assertSame(12, substr_count($response->getContent(), 'data-featured-investor'));
    }
}
