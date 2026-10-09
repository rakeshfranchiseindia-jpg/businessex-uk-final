<?php

namespace Tests\Feature;

use App\Models\UserAccount;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Mail\VerifyAccountEmail;
use App\Mail\ResetAccountPasswordEmail;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('user_account', function (Blueprint $table): void {
            $table->increments('user_id');
            $table->string('user_rand_id', 20)->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('mobile')->nullable();
            $table->string('company_name');
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('reg_source')->nullable();
            $table->string('reg_profile')->nullable();
            $table->timestamp('last_notify_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table): void {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('profile_media', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('profile_type', 32);
            $table->unsignedInteger('profile_id');
            $table->string('field_name', 100);
            $table->unsignedSmallInteger('file_index')->default(0);
            $table->string('media_type', 20);
            $table->string('disk', 32)->default('s3');
            $table->string('object_key', 1024);
            $table->text('url')->nullable();
            $table->string('original_name', 255);
            $table->string('mime_type', 255);
            $table->unsignedBigInteger('file_size');
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        Schema::create('user_profiles', function (Blueprint $table): void {
            $table->increments('user_prof_id');
            $table->integer('user_id');
            $table->integer('profile_id');
            $table->tinyInteger('profile_type');
            $table->string('profile_str', 20);
            $table->tinyInteger('profile_status');
            $table->timestamps();
        });

        Schema::create('profile_mentors', function (Blueprint $table): void {
            $table->increments('mentor_id');
            $table->string('mentor_profile_str', 20)->unique();
            $table->integer('user_id');
            $table->string('mentor_name');
            $table->string('mentor_mobile')->nullable();
            $table->string('mentor_email')->nullable();
            $table->string('mentor_location')->nullable();
            $table->string('mentor_city')->nullable();
            $table->string('mentor_state')->nullable();
            $table->string('mentor_country')->nullable();
            $table->string('mentor_adv_headline')->nullable();
            $table->string('mentor_intro')->nullable();
            $table->tinyInteger('mentor_occupation')->nullable();
            $table->string('mentor_company')->nullable();
            $table->string('mentor_designation')->nullable();
            $table->text('mentor_profile_summary')->nullable();
            $table->string('mentor_profile_pic')->nullable();
            $table->string('mentor_linkedin')->nullable();
            $table->tinyInteger('mentor_profile_status');
            $table->tinyInteger('membership_paid')->default(0);
            $table->tinyInteger('membership_plan')->default(0);
            $table->timestamps();
        });

        Schema::create('profile_business', function (Blueprint $table): void {
            $table->increments('business_id');
            $table->string('business_profile_str', 20)->unique();
            $table->integer('user_id');
            $table->string('seller_name');
            $table->string('seller_email')->nullable();
            $table->string('seller_mobile')->nullable();
            $table->string('seller_company')->nullable();
            $table->string('advmt_headline')->nullable();
            $table->integer('estb_year')->nullable();
            $table->string('emp_count')->nullable();
            $table->string('entity_type')->nullable();
            $table->string('business_type')->nullable();
            $table->string('industry_sector')->nullable();
            $table->string('mentor_support_field')->nullable();
            $table->string('support_field')->nullable();
            $table->decimal('annual_sales', 16, 4)->default(0);
            $table->string('ofc_country');
            $table->string('seller_prof_pic')->nullable();
            $table->string('seller_prof_pic1')->nullable();
            $table->string('seller_prof_thumb_pic')->nullable();
            $table->string('seller_prof_thumb_pic1')->nullable();
            $table->string('seller_doc_path')->nullable();
            $table->string('seller_doc_path1')->nullable();
            $table->string('seller_doc_path2')->nullable();
            $table->string('seller_doc_path3')->nullable();
            $table->tinyInteger('business_profile_status')->nullable();
            $table->tinyInteger('membership_paid')->default(0);
            $table->tinyInteger('membership_plan')->default(0);
            $table->timestamps();
        });

        Schema::create('profile_investor', function (Blueprint $table): void {
            $table->increments('investor_id');
            $table->string('inv_profile_str', 20)->unique();
            $table->integer('user_id');
            $table->string('inv_name')->nullable();
            $table->string('inv_email')->nullable();
            $table->string('inv_mobile')->nullable();
            $table->string('inv_city')->nullable();
            $table->string('inv_headline')->nullable();
            $table->tinyInteger('inv_type')->default(0);
            $table->tinyInteger('inv_profile_status');
            $table->tinyInteger('membership_paid')->default(0);
            $table->tinyInteger('membership_plan')->default(0);
            $table->string('company_logo_path')->nullable();
            $table->string('inv_profile_pic_path')->nullable();
            $table->timestamps();
        });

        Schema::create('profile_business_mgmt', function (Blueprint $table): void {
            $table->increments('business_mgmt_id');
            $table->integer('business_profile_id');
            $table->integer('user_id');
            $table->string('mgmt_name')->nullable();
            $table->string('mgmt_designation')->nullable();
            $table->string('mgmt_email')->nullable();
            $table->timestamps();
        });

        Schema::create('profile_startups', function (Blueprint $table): void {
            $table->increments('startup_id');
            $table->string('startup_profile_str', 20)->unique();
            $table->integer('user_id');
            $table->string('startup_name');
            $table->string('startup_designation')->nullable();
            $table->string('startup_mobile')->nullable();
            $table->string('startup_email')->nullable();
            $table->string('advmt_headline')->nullable();
            $table->string('name_of_entity')->nullable();
            $table->string('startup_prof_pic')->nullable();
            $table->string('startup_prof_pic1')->nullable();
            $table->string('startup_prof_thumb_pic')->nullable();
            $table->string('startup_prof_thumb_pic1')->nullable();
            $table->string('startup_doc_path')->nullable();
            $table->tinyInteger('startup_profile_status')->nullable();
            $table->tinyInteger('membership_paid')->default(0);
            $table->tinyInteger('membership_plan')->default(0);
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('profile_mentors');
        Schema::dropIfExists('profile_startups');
        Schema::dropIfExists('profile_business_mgmt');
        Schema::dropIfExists('profile_investor');
        Schema::dropIfExists('profile_business');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('user_account');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('profile_media');

        parent::tearDown();
    }

    public function test_registration_creates_an_account_profile_and_ownership_link(): void
    {
        Mail::fake();
        $payload = $this->mentorRegistrationPayload();
        $payload['mentor_email'] = 'New.Member@Example.com';

        $this->post(route('registration.store', 'mentor'), $payload)
            ->assertRedirect(route('mentor-registration'))
            ->assertSessionHas('verification_notice');
        $this->get(route('mentor-registration'))
            ->assertOk()
            ->assertSee('Please check your inbox and verify your email address before signing in.');

        $account = DB::table('user_account')->where('email', 'new.member@example.com')->first();
        $this->assertNotNull($account);
        $this->assertNull($account->password);
        $this->assertSame('mentor', $account->reg_profile);
        $this->assertSame(1, (int) $account->is_active);
        $this->assertNull($account->email_verified_at);
        $this->assertGuest();
        Mail::assertQueued(VerifyAccountEmail::class, fn (VerifyAccountEmail $mail): bool =>
            $mail->hasTo('new.member@example.com')
            && str_contains($mail->verificationUrl, '/email/verify/')
            && str_contains($mail->render(), 'Verify email address')
        );
        $this->assertDatabaseHas('profile_mentors', [
            'user_id' => $account->user_id,
            'mentor_name' => 'Taylor Mentor',
            'mentor_email' => 'new.member@example.com',
            'mentor_profile_status' => 0,
        ]);
        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $account->user_id,
            'profile_type' => 4,
            'profile_status' => 1,
        ]);
    }

    public function test_quick_registration_creates_a_profile_and_queues_verification_email(): void
    {
        Mail::fake();

        $profiles = [
            'business' => ['profile_business', 'seller_name', 'seller_email', 'seller_mobile', 'seller_company', 1],
            'investor' => ['profile_investor', 'inv_name', 'inv_email', 'inv_mobile', 'company_name', 2],
            'startup' => ['profile_startups', 'startup_name', 'startup_email', 'startup_mobile', 'name_of_entity', 7],
            'mentor' => ['profile_mentors', 'mentor_name', 'mentor_email', 'mentor_mobile', 'mentor_company', 4],
        ];

        foreach ($profiles as $type => [$table, $nameColumn, $emailColumn, $mobileColumn, $companyColumn, $profileType]) {
            $email = "quick-{$type}@example.com";
            $name = 'Quick ' . ucfirst($type);
            $company = 'Quick ' . ucfirst($type) . ' Company';

            $this->post(route('registration.quick-store'), [
                '_quick_registration' => '1',
                'profile_type' => $type,
                'name' => $name,
                'email' => strtoupper($email),
                'mobile' => '+441234567890',
                'company_name' => $company,
            ])
                ->assertRedirect(route('home'))
                ->assertSessionHas('verification_notice');

            $account = DB::table('user_account')->where('email', $email)->first();
            $this->assertNotNull($account);
            $this->assertSame($type, $account->reg_profile);
            $this->assertNull($account->password);
            $this->assertNull($account->email_verified_at);
            $profileValues = [
                'user_id' => $account->user_id,
                $nameColumn => $name,
                $emailColumn => $email,
                $mobileColumn => '+441234567890',
                $companyColumn => $company,
            ];
            $this->assertDatabaseHas(
                $table,
                array_intersect_key($profileValues, array_flip(Schema::getColumnListing($table)))
            );
            $this->assertDatabaseHas('user_profiles', [
                'user_id' => $account->user_id,
                'profile_type' => $profileType,
            ]);
        }

        Mail::assertQueued(VerifyAccountEmail::class, 4);
    }

    public function test_login_accepts_active_legacy_account_password_and_logout_clears_session(): void
    {
        $accountId = DB::table('user_account')->insertGetId([
            'user_rand_id' => 'TESTACCOUNT000000001',
            'name' => 'Existing Member',
            'email' => 'existing@example.com',
            'password' => Hash::make('ExistingPass123'),
            'company_name' => 'Existing Company',
            'is_active' => 1,
            'email_verified_at' => now(),
            'last_notify_at' => now(),
            'last_login_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->post(route('login.store'), [
            'email' => 'existing@example.com',
            'password' => 'ExistingPass123',
        ])->assertRedirect(route('dashboard.index'));

        $this->assertAuthenticatedAs(UserAccount::query()->findOrFail($accountId));

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invalid_login_does_not_authenticate(): void
    {
        $this->post(route('login.store'), [
            'email' => 'unknown@example.com',
            'password' => 'WrongPass123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_rejects_correct_password_until_email_is_verified(): void
    {
        $accountId = DB::table('user_account')->insertGetId([
            'user_rand_id' => 'TESTUNVERIFIED000001',
            'name' => 'Unverified Member',
            'email' => 'unverified@example.com',
            'password' => Hash::make('ExistingPass123'),
            'company_name' => 'Unverified Company',
            'is_active' => 1,
            'email_verified_at' => null,
            'last_notify_at' => now(),
            'last_login_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->post(route('login.store'), [
            'email' => 'unverified@example.com',
            'password' => 'ExistingPass123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertDatabaseHas('user_account', ['user_id' => $accountId, 'email_verified_at' => null]);
    }

    public function test_verification_redirects_new_account_to_signed_password_setup(): void
    {
        $accountId = DB::table('user_account')->insertGetId([
            'user_rand_id' => 'TESTVERIFY0000000001',
            'name' => 'Verification Member',
            'email' => 'verify@example.com',
            'password' => null,
            'company_name' => 'Verification Company',
            'is_active' => 1,
            'email_verified_at' => null,
            'last_notify_at' => now(),
            'last_login_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $accountId, 'hash' => sha1('verify@example.com')]
        );

        $verificationResponse = $this->get($url)
            ->assertRedirect();

        $this->assertNotNull(DB::table('user_account')->where('user_id', $accountId)->value('email_verified_at'));
        $this->get($url . '&extra=invalid')->assertForbidden();

        $passwordSetupUrl = $verificationResponse->headers->get('Location');
        $this->assertStringContainsString('/account/set-password/' . $accountId . '/', $passwordSetupUrl);
        $this->get($passwordSetupUrl)
            ->assertOk()
            ->assertSee('Set Your Password')
            ->assertSee('Your email is verified. Choose a password to finish setting up your account.');

        $this->post($passwordSetupUrl, [
            'password' => 'NewSecurePass123',
            'password_confirmation' => 'NewSecurePass123',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('status', 'Your email is verified and password is set. You can now sign in.');

        $account = DB::table('user_account')->where('user_id', $accountId)->first();
        $this->assertTrue(Hash::check('NewSecurePass123', $account->password));
    }

    public function test_user_can_request_another_verification_email(): void
    {
        Mail::fake();
        DB::table('user_account')->insert([
            'user_rand_id' => 'TESTRESEND0000000001',
            'name' => 'Resend Member',
            'email' => 'resend@example.com',
            'password' => Hash::make('ExistingPass123'),
            'company_name' => 'Resend Company',
            'is_active' => 1,
            'email_verified_at' => null,
            'last_notify_at' => now(),
            'last_login_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->post(route('verification.resend'), ['email' => 'RESEND@example.com'])
            ->assertRedirect(route('login'))
            ->assertSessionHas('verification_notice');

        Mail::assertQueued(VerifyAccountEmail::class, fn (VerifyAccountEmail $mail): bool =>
            $mail->hasTo('resend@example.com')
        );
    }

    public function test_password_reset_email_is_queued_and_reset_link_updates_password_once(): void
    {
        Mail::fake();
        $accountId = DB::table('user_account')->insertGetId([
            'user_rand_id' => 'TESTPASSRESET000001',
            'name' => 'Password Reset Member',
            'email' => 'password-reset@example.com',
            'password' => Hash::make('OriginalPass123'),
            'company_name' => 'Password Reset Company',
            'is_active' => 1,
            'email_verified_at' => now(),
            'last_notify_at' => now(),
            'last_login_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get(route('forgot-password'))
            ->assertOk()
            ->assertSee(route('password.email'), false)
            ->assertSee('Send Reset Link');

        $this->post(route('password.email'), ['email' => 'PASSWORD-RESET@example.com'])
            ->assertRedirect(route('forgot-password'))
            ->assertSessionHas('status', 'If an account exists for that email address, a password reset link will be sent shortly.');

        $this->assertDatabaseHas('password_reset_tokens', ['email' => 'password-reset@example.com']);
        Mail::assertQueued(ResetAccountPasswordEmail::class, fn (ResetAccountPasswordEmail $mail): bool =>
            $mail->hasTo('password-reset@example.com')
            && str_contains($mail->render(), '/reset-password/')
        );
        $queuedMail = Mail::queued(ResetAccountPasswordEmail::class)->first();
        $this->assertNotNull($queuedMail);

        $resetUrl = route('reset-password', [
            'token' => $queuedMail->token,
            'email' => 'password-reset@example.com',
        ]);
        $this->get($resetUrl)
            ->assertOk()
            ->assertSee(route('password.update'), false)
            ->assertSee('Create a New Password');

        $this->post(route('password.update'), [
            'token' => $queuedMail->token,
            'email' => 'password-reset@example.com',
            'password' => 'NewStrongPass456',
            'password_confirmation' => 'NewStrongPass456',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('status', 'Your password has been reset. You can now sign in with your new password.');

        $this->assertTrue(Hash::check(
            'NewStrongPass456',
            DB::table('user_account')->where('user_id', $accountId)->value('password')
        ));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'password-reset@example.com']);

        $this->post(route('password.update'), [
            'token' => $queuedMail->token,
            'email' => 'password-reset@example.com',
            'password' => 'AnotherStrongPass789',
            'password_confirmation' => 'AnotherStrongPass789',
        ])->assertSessionHasErrors('email');

        $this->post(route('login.store'), [
            'email' => 'password-reset@example.com',
            'password' => 'NewStrongPass456',
        ])->assertRedirect(route('dashboard.index'));
    }

    public function test_password_reset_does_not_disclose_unknown_email_and_rejects_invalid_token(): void
    {
        Mail::fake();
        $this->from(route('forgot-password'))
            ->post(route('password.email'), ['email' => 'unknown@example.com'])
            ->assertRedirect(route('forgot-password'))
            ->assertSessionHas('status', 'If an account exists for that email address, a password reset link will be sent shortly.');
        Mail::assertNothingQueued();

        $this->get(route('reset-password', [
            'token' => 'not-a-valid-token',
            'email' => 'unknown@example.com',
        ]))->assertOk();

        $this->post(route('password.update'), [
            'token' => 'not-a-valid-token',
            'email' => 'unknown@example.com',
            'password' => 'NewStrongPass456',
            'password_confirmation' => 'NewStrongPass456',
        ])->assertSessionHasErrors('email');
    }

    public function test_registration_rejects_duplicate_email_without_requiring_a_password(): void
    {
        DB::table('user_account')->insert([
            'user_rand_id' => 'TESTACCOUNT000000002',
            'name' => 'Existing Member',
            'email' => 'existing@example.com',
            'password' => Hash::make('ExistingPass123'),
            'company_name' => 'Existing Company',
            'is_active' => 1,
            'last_notify_at' => now(),
            'last_login_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $payload = $this->mentorRegistrationPayload();
        $payload['mentor_email'] = 'existing@example.com';
        $this->post(route('registration.store', 'mentor'), $payload)
            ->assertSessionHasErrors('mentor_email');

        $this->assertSame(1, DB::table('user_account')->count());
        $this->assertSame(0, DB::table('profile_mentors')->count());
        $this->assertGuest();
    }

    public function test_each_registration_type_creates_its_legacy_profile_and_mapping(): void
    {
        Mail::fake();
        $types = [
            'business' => 1,
            'investor' => 2,
            'mentor' => 4,
            'startup' => 7,
        ];

        foreach ($types as $type => $legacyType) {
            $email = $type . '@example.com';
            $payload = $this->registrationPayload($type, $email);

            $this->post(route('registration.store', $type), $payload)
                ->assertRedirect(route("{$type}-registration"))
                ->assertSessionHas('verification_notice');

            $account = DB::table('user_account')->where('email', $email)->first();
            $this->assertNotNull($account);
            $this->assertDatabaseHas('user_profiles', [
                'user_id' => $account->user_id,
                'profile_type' => $legacyType,
            ]);
        }

        Mail::assertQueued(VerifyAccountEmail::class, 4);
    }

    public function test_business_registration_stores_all_six_step_data_and_attachments(): void
    {
        Mail::fake();
        Storage::fake('s3');
        config(['filesystems.cdn_url' => 'https://cdn.example.test']);
        $payload = $this->registrationPayload('business', 'owner@example.com', [
            'seller_name' => 'Morgan Owner',
            'seller_company' => 'Example Ltd',
            'contact_name' => 'Avery Contact',
            'contact_designation' => 'Finance Manager',
            'contact_email' => 'avery@example.com',
            'annual_sales' => '120000.50',
            'mentor_support_field' => 'Sales mentoring',
            'support_field' => 'Expansion capital',
            'business_photo_1' => UploadedFile::fake()->image('business.png'),
        ]);

        $this->post(route('business-profile.store'), $payload)
            ->assertRedirect(route('business-registration'))
            ->assertSessionHas('verification_notice');

        $account = DB::table('user_account')->where('email', 'owner@example.com')->first();
        $profile = DB::table('profile_business')->where('user_id', $account->user_id)->first();
        $this->assertSame('Example Ltd', $profile->seller_company);
        $this->assertEquals(120000.5, $profile->annual_sales);
        $this->assertSame('Sales mentoring', $profile->mentor_support_field);
        $this->assertSame('Expansion capital', $profile->support_field);
        $this->assertNotEmpty($profile->seller_prof_pic);
        $media = DB::table('profile_media')
            ->where('user_id', $account->user_id)
            ->where('field_name', 'business_photo_1')
            ->first();
        $this->assertNotNull($media);
        $this->assertTrue((bool) $media->is_public);
        $this->assertStringStartsWith('https://cdn.example.test/profiles/business/images/', $profile->seller_prof_pic);
        Storage::disk('s3')->assertExists($media->object_key);
        $this->assertDatabaseHas('profile_business_mgmt', [
            'business_profile_id' => $profile->business_id,
            'user_id' => $account->user_id,
            'mgmt_name' => 'Avery Contact',
            'mgmt_designation' => 'Finance Manager',
            'mgmt_email' => 'avery@example.com',
        ]);
        $this->assertGuest();
        Mail::assertQueued(VerifyAccountEmail::class, fn (VerifyAccountEmail $mail): bool =>
            $mail->hasTo('owner@example.com')
        );
    }

    public function test_authenticated_user_can_create_another_profile_without_creating_another_account(): void
    {
        $userId = DB::table('user_account')->insertGetId([
            'user_rand_id' => 'EXISTINGPROFILE0001',
            'name' => 'Existing Account Owner',
            'email' => 'existing-account@example.com',
            'password' => Hash::make('AccountPass123!'),
            'mobile' => '+447700900123',
            'company_name' => 'Existing Account Ltd',
            'is_active' => 1,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $payload = $this->registrationPayload('business', 'existing-account@example.com', [
            'seller_name' => 'Existing Account Owner',
            'seller_company' => 'New Business Profile Ltd',
        ]);

        $this->actingAs(UserAccount::query()->findOrFail($userId))
            ->get(route('dashboard.profiles.create.form', 'business'))
            ->assertOk()
            ->assertSee('value="Existing Account Owner"', false)
            ->assertSee('value="existing-account@example.com"', false);

        $this->post(route('dashboard.profiles.store', 'business'), $payload)
            ->assertRedirect(route('dashboard.index'))
            ->assertSessionHas('status', 'Business profile created successfully.');

        $this->assertSame(1, DB::table('user_account')->where('email', 'existing-account@example.com')->count());
        $profile = DB::table('profile_business')
            ->where('user_id', $userId)
            ->where('seller_company', 'New Business Profile Ltd')
            ->first();
        $this->assertNotNull($profile);
        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $userId,
            'profile_id' => $profile->business_id,
            'profile_type' => 1,
            'profile_str' => $profile->business_profile_str,
        ]);
        $this->assertDatabaseCount('profile_business', 1);
        $this->assertAuthenticatedAs(UserAccount::query()->findOrFail($userId));
    }

    public function test_registration_uploads_all_profile_media_to_the_profile_s3_prefixes(): void
    {
        Mail::fake();
        Storage::fake('s3');
        config(['filesystems.cdn_url' => 'https://cdn.example.test']);

        $business = $this->registrationPayload('business', 'media-business@example.com', [
            'business_photo_1' => UploadedFile::fake()->image('business.png'),
            'business_document_1' => UploadedFile::fake()->create('business-proof.pdf', 20, 'application/pdf'),
        ]);
        $this->post(route('business-profile.store'), $business)->assertRedirect(route('business-registration'));

        $investor = $this->registrationPayload('investor', 'media-investor@example.com', [
            'company_logo' => UploadedFile::fake()->image('investor-logo.png'),
            'profile_pictures' => [
                UploadedFile::fake()->image('investor-one.png'),
                UploadedFile::fake()->image('investor-two.png'),
            ],
        ]);
        $this->post(route('investor-profile.store'), $investor)->assertRedirect(route('investor-registration'));

        $mentor = $this->registrationPayload('mentor', 'media-mentor@example.com', [
            'mentor_profile_image' => UploadedFile::fake()->image('mentor.png'),
        ]);
        $this->post(route('mentor-profile.store'), $mentor)->assertRedirect(route('mentor-registration'));

        $startup = $this->registrationPayload('startup', 'media-startup@example.com', [
            'incorporation_certificate' => UploadedFile::fake()->create('certificate.pdf', 20, 'application/pdf'),
            'startup_photo_1' => UploadedFile::fake()->image('startup.png'),
            'startup_document_1' => UploadedFile::fake()->create('startup-plan.pdf', 20, 'application/pdf'),
        ]);
        $this->post(route('startup-profile.store'), $startup)->assertRedirect(route('startup-registration'));

        $this->assertDatabaseHas('profile_media', [
            'profile_type' => 'business',
            'field_name' => 'business_photo_1',
            'media_type' => 'image',
            'is_public' => 1,
        ]);
        $this->assertDatabaseHas('profile_media', [
            'profile_type' => 'business',
            'field_name' => 'business_document_1',
            'media_type' => 'document',
            'is_public' => 0,
        ]);
        $this->assertDatabaseHas('profile_media', [
            'profile_type' => 'investor',
            'field_name' => 'company_logo',
            'media_type' => 'image',
            'is_public' => 1,
        ]);
        $this->assertDatabaseHas('profile_media', [
            'profile_type' => 'investor',
            'field_name' => 'profile_pictures',
            'file_index' => 1,
            'media_type' => 'image',
            'is_public' => 1,
        ]);
        $this->assertDatabaseHas('profile_media', [
            'profile_type' => 'mentor',
            'field_name' => 'mentor_profile_image',
            'media_type' => 'image',
            'is_public' => 1,
        ]);
        $this->assertDatabaseHas('profile_media', [
            'profile_type' => 'startup',
            'field_name' => 'incorporation_certificate',
            'media_type' => 'document',
            'is_public' => 0,
        ]);
        $this->assertDatabaseHas('profile_media', [
            'profile_type' => 'startup',
            'field_name' => 'startup_photo_1',
            'media_type' => 'image',
            'is_public' => 1,
        ]);

        $allMedia = DB::table('profile_media')->get();
        $this->assertCount(9, $allMedia);
        foreach ($allMedia as $media) {
            $directory = $media->media_type === 'image' ? 'images' : 'documents';
            $this->assertStringStartsWith(
                "profiles/{$media->profile_type}/{$directory}/",
                $media->object_key
            );
            if ($media->media_type === 'image') {
                $this->assertStringStartsWith(
                    'https://cdn.example.test/' . $media->object_key,
                    $media->url
                );
            } else {
                $this->assertStringContainsString(
                    '/profile-media/' . $media->id . '/download',
                    $media->url
                );
            }
        }

        $businessProfile = DB::table('profile_business')
            ->where('seller_email', 'media-business@example.com')
            ->first();
        $businessImage = DB::table('profile_media')
            ->where('profile_type', 'business')
            ->where('field_name', 'business_photo_1')
            ->first();
        $businessDocument = DB::table('profile_media')
            ->where('profile_type', 'business')
            ->where('field_name', 'business_document_1')
            ->first();

        $this->assertStringStartsWith(
            'https://cdn.example.test/profiles/business/images/',
            $businessProfile->seller_prof_pic
        );
        $this->assertSame($businessProfile->seller_prof_pic, $businessImage->url);
        $this->assertSame($businessDocument->url, $businessProfile->seller_doc_path);
        Storage::disk('s3')->assertExists($businessImage->object_key);
        Storage::disk('s3')->assertExists($businessDocument->object_key);
        $this->get($businessDocument->url)->assertRedirect(route('login'));
        $this->actingAs(UserAccount::query()->findOrFail($businessDocument->user_id))
            ->get($businessDocument->url)
            ->assertRedirect();
        $this->actingAs(UserAccount::query()->findOrFail(
            DB::table('user_account')->where('email', 'media-mentor@example.com')->value('user_id')
        ))->get($businessDocument->url)->assertForbidden();

        $investorProfile = DB::table('profile_investor')
            ->where('inv_email', 'media-investor@example.com')
            ->first();
        $mentorProfile = DB::table('profile_mentors')
            ->where('mentor_email', 'media-mentor@example.com')
            ->first();
        $startupProfile = DB::table('profile_startups')
            ->where('startup_email', 'media-startup@example.com')
            ->first();
        $this->assertStringStartsWith('https://cdn.example.test/profiles/investor/images/', $investorProfile->company_logo_path);
        $this->assertStringStartsWith('https://cdn.example.test/profiles/investor/images/', $investorProfile->inv_profile_pic_path);
        $this->assertStringStartsWith('https://cdn.example.test/profiles/mentor/images/', $mentorProfile->mentor_profile_pic);
        $this->assertStringContainsString('/profile-media/', $startupProfile->startup_doc_path);
        $this->assertStringStartsWith('https://cdn.example.test/profiles/startup/images/', $startupProfile->startup_prof_pic);
    }

    public function test_business_registration_returns_field_errors_for_invalid_required_and_numeric_fields(): void
    {
        $payload = $this->registrationPayload('business', 'owner@example.com', [
            'seller_name' => '',
            'annual_sales' => '-25',
        ]);

        $this->post(route('business-profile.store'), $payload)
            ->assertSessionHasErrors(['seller_name', 'annual_sales']);

        $this->assertSame(0, DB::table('user_account')->count());
        $this->assertGuest();
    }

    public function test_business_registration_rejects_an_attachment_with_an_unapproved_type(): void
    {
        Storage::fake('s3');
        $payload = $this->registrationPayload('business', 'owner@example.com', [
            'business_photo_1' => UploadedFile::fake()->create('not-an-image.txt', 10),
        ]);

        $this->post(route('business-profile.store'), $payload)
            ->assertSessionHasErrors('business_photo_1');

        $this->assertSame(0, DB::table('user_account')->count());
        Storage::disk('s3')->assertDirectoryEmpty('profiles/business/images');
    }

    public function test_registration_page_renders_the_real_posting_form(): void
    {
        $this->get(route('mentor-registration'))
            ->assertOk()
            ->assertSee(route('mentor-profile.store'), false)
            ->assertDontSee('name="password"', false)
            ->assertSee('name="_token"', false);

        $this->get(route('business-registration'))
            ->assertOk()
            ->assertSee(route('business-profile.store'), false)
            ->assertSee('data-phone-country-code', false)
            ->assertSee('UK +44')
            ->assertSee('data-step="6"', false)
            ->assertSee('name="annual_sales"', false)
            ->assertSee('name="business_photo_1"', false);

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('data-quick-register="business"', false)
            ->assertSee('data-quick-register="investor"', false)
            ->assertSee('data-quick-register="startup"', false)
            ->assertSee('data-quick-register="mentor"', false)
            ->assertSee(route('registration.quick-store'), false)
            ->assertSee('data-phone-country-code', false)
            ->assertSee('name="company_name"', false);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('id="heroRegCard" method="post"', false)
            ->assertSee('name="profile_type"', false)
            ->assertSee('name="name"', false)
            ->assertSee('name="email"', false)
            ->assertSee('name="mobile"', false)
            ->assertSee('data-phone-country-code', false)
            ->assertSee('UK +44')
            ->assertSee('name="company_name"', false)
            ->assertDontSee('name="password"', false)
            ->assertDontSee('name="password_confirmation"', false);
    }

    private function mentorRegistrationPayload(): array
    {
        return $this->registrationPayload('mentor', 'taylor@example.com', [
            'mentor_name' => 'Taylor Mentor',
            'mentor_email' => 'taylor@example.com',
            'mentor_mobile' => '+441234567890',
            'mentor_location' => 'London',
            'mentor_adv_headline' => 'Experienced business mentor',
            'mentor_occupation' => 'Corporate Professional',
        ]);
    }

    private function registrationPayload(string $type, string $email, array $overrides = []): array
    {
        $profile = config("registration_profiles.{$type}");
        $payload = $overrides;

        foreach ($profile['steps'] as $step) {
            foreach ($step['fields'] as $field) {
                if (empty($field['required']) || !isset($field['name']) || isset($payload[$field['name']])) {
                    continue;
                }

                $payload[$field['name']] = ($field['type'] ?? null) === 'select'
                    ? (string) $field['options'][0]
                    : 'Test value';
            }
        }

        $emailField = [
            'business' => 'seller_email',
            'investor' => 'inv_email',
            'mentor' => 'mentor_email',
            'startup' => 'startup_email',
        ][$type];
        $payload[$emailField] = $email;

        return $payload;
    }
}
