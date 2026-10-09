<?php

namespace Tests\Feature;

use Database\Seeders\ProfileRecordsSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProfileRecordsSeederTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('user_account', function (Blueprint $table): void {
            $table->increments('user_id');
            $table->string('user_rand_id')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('mobile')->nullable();
            $table->string('company_name');
            $table->boolean('is_active');
            $table->integer('reg_source')->nullable();
            $table->string('reg_profile');
            $table->timestamp('last_notify_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('user_profiles', function (Blueprint $table): void {
            $table->increments('user_prof_id');
            $table->integer('user_id');
            $table->integer('profile_id');
            $table->tinyInteger('profile_type');
            $table->string('profile_str')->unique();
            $table->tinyInteger('profile_status');
            $table->timestamps();
        });

        Schema::create('profile_business', function (Blueprint $table): void {
            $table->increments('business_id');
            $table->string('business_profile_str')->unique();
            $table->integer('user_id');
            $table->string('seller_name');
            $table->string('seller_email');
            $table->string('seller_company');
            $table->string('ofc_country');
            $table->tinyInteger('business_profile_status');
            $table->timestamps();
        });

        Schema::create('profile_mentors', function (Blueprint $table): void {
            $table->increments('mentor_id');
            $table->string('mentor_profile_str')->unique();
            $table->integer('user_id');
            $table->string('mentor_name');
            $table->string('mentor_email');
            $table->tinyInteger('mentor_profile_status');
            $table->timestamps();
        });

        Schema::create('profile_investor', function (Blueprint $table): void {
            $table->increments('investor_id');
            $table->string('inv_profile_str')->unique();
            $table->integer('user_id');
            $table->string('inv_name');
            $table->string('inv_email');
            $table->tinyInteger('inv_profile_status');
            $table->timestamps();
        });

        Schema::create('profile_startups', function (Blueprint $table): void {
            $table->increments('startup_id');
            $table->string('startup_profile_str')->unique();
            $table->integer('user_id');
            $table->string('startup_name');
            $table->string('startup_email');
            $table->tinyInteger('startup_profile_status');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('profile_startups');
        Schema::dropIfExists('profile_investor');
        Schema::dropIfExists('profile_mentors');
        Schema::dropIfExists('profile_business');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('user_account');

        parent::tearDown();
    }

    public function test_seeder_creates_five_linked_profiles_of_each_type_and_is_safe_to_rerun(): void
    {
        $seeder = new ProfileRecordsSeeder();
        $seeder->run();

        foreach (['profile_business', 'profile_mentors', 'profile_investor', 'profile_startups'] as $table) {
            $this->assertDatabaseCount($table, 5);
        }
        $this->assertDatabaseCount('user_account', 20);
        $this->assertDatabaseCount('user_profiles', 20);
        $this->assertSame(20, DB::table('user_profiles')->where('profile_status', 1)->count());

        $demoBusinessUser = DB::table('user_account')
            ->where('email', 'demo.business05@example.test')
            ->first();
        $this->assertNotNull($demoBusinessUser);
        $this->assertTrue((bool) $demoBusinessUser->is_active);
        $this->assertNotNull($demoBusinessUser->email_verified_at);
        $this->assertTrue(Hash::check('P@ssw0rd@123', $demoBusinessUser->password));

        $seeder->run();

        foreach (['profile_business', 'profile_mentors', 'profile_investor', 'profile_startups'] as $table) {
            $this->assertDatabaseCount($table, 5);
        }
        $this->assertDatabaseCount('user_account', 20);
        $this->assertDatabaseCount('user_profiles', 20);
    }
}
