<?php

namespace Tests\Feature;

use App\Support\PublicContentCache;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PublicContentCacheTest extends TestCase
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

    public function test_home_page_is_served_from_cache_until_profile_content_changes(): void
    {
        $this->addOpportunity('First Opportunity');

        $this->get(route('home'))->assertOk()->assertSee('First Opportunity');

        $queries = 0;
        DB::listen(function () use (&$queries): void {
            $queries++;
        });

        // Written behind the app's back: still the cached page.
        $this->addOpportunity('Second Opportunity');
        $this->get(route('home'))->assertOk()->assertDontSee('Second Opportunity');
        $this->assertLessThanOrEqual(1, $queries, 'A cached home page should not query the content tables.');

        PublicContentCache::forgetProfileContent();

        $this->get(route('home'))->assertOk()->assertSee('Second Opportunity');
    }

    private function addOpportunity(string $headline): void
    {
        DB::table('profile_business')->insert([
            'business_profile_str' => uniqid('HOME-', true),
            'business_profile_status' => 1,
            'created_at' => now(),
            'advmt_headline' => $headline,
            'seller_company' => $headline.' Ltd',
            'industry_sector' => 'Retail',
            'inv_asking_price' => 1000,
            'ofc_city' => 'London',
        ]);
    }
}
