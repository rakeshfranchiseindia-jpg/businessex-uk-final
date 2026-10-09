<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomeWorldClassMentorsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('profile_mentors', function (Blueprint $table): void {
            $table->increments('mentor_id');
            $table->tinyInteger('mentor_profile_status');
            $table->string('mentor_name');
            $table->string('mentor_company')->nullable();
            $table->string('mentor_designation')->nullable();
            $table->string('mentor_city')->nullable();
            $table->string('mentor_adv_headline')->nullable();
            $table->string('mentor_intro')->nullable();
            $table->text('mentor_profile_summary')->nullable();
            $table->string('mentor_profile_pic')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('profile_mentors');

        parent::tearDown();
    }

    public function test_homepage_shows_the_twelve_latest_active_mentors(): void
    {
        for ($id = 1; $id <= 15; $id++) {
            DB::table('profile_mentors')->insert([
                'mentor_profile_status' => $id === 15 ? 0 : 1,
                'mentor_name' => 'Homepage Mentor ' . str_pad((string) $id, 2, '0', STR_PAD_LEFT),
                'mentor_company' => 'Mentor Company ' . $id,
                'mentor_designation' => 'Growth Adviser',
                'mentor_city' => 'London',
                'mentor_adv_headline' => 'Headline for mentor ' . $id,
                'mentor_intro' => 'Introduction for mentor ' . $id,
                'mentor_profile_summary' => 'Summary for mentor ' . $id,
                'mentor_profile_pic' => null,
                'created_at' => now()->subDays(15 - $id),
                'updated_at' => now(),
                'deleted_at' => $id === 14 ? now() : null,
            ]);
        }

        $response = $this->get(route('home'))
            ->assertOk()
            ->assertSee('World Class Mentors')
            ->assertSee('13 mentors')
            ->assertSee('Homepage Mentor 13')
            ->assertSee('Growth Adviser')
            ->assertSee('London')
            ->assertSee('Summary for mentor 13')
            ->assertSee(asset('assets/img/default-mentor-profile.png'))
            ->assertDontSee('Homepage Mentor 01')
            ->assertDontSee('Homepage Mentor 14')
            ->assertDontSee('Homepage Mentor 15');

        $this->assertSame(12, substr_count($response->getContent(), 'data-world-class-mentor'));
    }
}
