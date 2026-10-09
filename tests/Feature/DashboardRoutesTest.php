<?php

namespace Tests\Feature;

use App\Models\UserAccount;
use App\Notifications\DashboardActivityNotification;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class DashboardRoutesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('user_account', function (Blueprint $table): void {
            $table->increments('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('mobile')->nullable();
            $table->string('location')->nullable();
            $table->string('designation')->nullable();
            $table->string('company_name');
            $table->boolean('is_active')->default(true);
            $table->string('reg_profile')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });

        $this->createProfileTable('profile_business', 'business_id', 'business_profile_status', ['advmt_headline', 'seller_company', 'estb_year']);
        $this->createProfileTable('profile_investor', 'investor_id', 'inv_profile_status', ['inv_headline']);
        $this->createProfileTable('profile_mentors', 'mentor_id', 'mentor_profile_status', ['mentor_adv_headline']);
        $this->createProfileTable('profile_startups', 'startup_id', 'startup_profile_status', ['advmt_headline']);
        Schema::create('profile_contact_conversations', function (Blueprint $table): void {
            $table->id();
            $table->string('profile_type', 30);
            $table->unsignedBigInteger('profile_id');
            $table->unsignedInteger('owner_user_id');
            $table->unsignedInteger('sender_user_id');
            $table->string('sender_name');
            $table->string('sender_email');
            $table->string('sender_phone', 30)->nullable();
            $table->timestamps();
        });
        Schema::create('profile_contact_messages', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedInteger('sender_user_id');
            $table->text('message');
            $table->timestamps();
        });
        Schema::create('profile_contact_conversation_reads', function (Blueprint $table): void {
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('last_read_message_id');
            $table->timestamps();
            $table->primary(['conversation_id', 'user_id']);
        });
        $this->createNotificationsTable();
        Schema::create('profile_instant_responses', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->primary();
            $table->boolean('enabled')->default(false);
            $table->string('message', 500);
            $table->timestamps();
        });
        Schema::create('membership_plans', function (Blueprint $table): void {
            $table->increments('plan_id');
            $table->integer('plan_type');
            $table->string('plan_name');
            $table->text('plan_desc');
            $table->integer('profile_type');
            $table->string('profile_name');
            $table->integer('validity_in_days');
            $table->string('plan_amount');
            $table->string('interaction_credits');
            $table->string('instant_responses');
            $table->boolean('is_active');
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();
        });
        Schema::create('profile_memberships', function (Blueprint $table): void {
            $table->increments('membership_id');
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('profile_type');
            $table->unsignedInteger('profile_id');
            $table->integer('order_no');
            $table->string('amount');
            $table->smallInteger('membership_type');
            $table->unsignedTinyInteger('payment_source');
            $table->string('payment_comments')->nullable();
            $table->string('interaction_credits')->nullable();
            $table->string('instant_responses')->nullable();
            $table->timestamp('activation_date')->useCurrent();
            $table->timestamp('expiry_date')->nullable();
            $table->boolean('is_active');
            $table->unsignedTinyInteger('upg_source')->default(1);
            $table->timestamps();
        });
        Schema::create('profile_proposals', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->string('profile_type', 30);
            $table->unsignedBigInteger('profile_id');
            $table->unsignedInteger('sender_user_id');
            $table->unsignedInteger('recipient_user_id');
            $table->string('title');
            $table->text('description');
            $table->decimal('amount', 16, 2)->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('profile_proposals');
        Schema::dropIfExists('profile_memberships');
        Schema::dropIfExists('membership_plans');
        Schema::dropIfExists('profile_instant_responses');
        Schema::dropIfExists('profile_contact_messages');
        Schema::dropIfExists('profile_contact_conversation_reads');
        Schema::dropIfExists('profile_contact_conversations');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('profile_business');
        Schema::dropIfExists('profile_investor');
        Schema::dropIfExists('profile_mentors');
        Schema::dropIfExists('profile_startups');
        Schema::dropIfExists('user_account');

        parent::tearDown();
    }

    public function test_dashboard_screens_use_the_dashboard_view_directory_and_layout(): void
    {
        $user = new UserAccount;
        $user->setAttribute('user_id', 1);
        $user->setAttribute('name', 'Test Member');
        $user->setAttribute('reg_profile', 'business');
        $this->actingAs($user);

        $screens = [
            ['/dashboard', 'dashboard.dashboard'],
            ['/dashboard/profile', 'dashboard.dashboard-profile'],
            ['/dashboard/password', 'dashboard.dashboard-password'],
            ['/dashboard/my-plan', 'dashboard.my-plan'],
            ['/dashboard/inbox', 'dashboard.interaction'],
            ['/dashboard/proposals/sent', 'dashboard.interaction'],
            ['/dashboard/proposals/received', 'dashboard.interaction'],
            ['/dashboard/instant-response', 'dashboard.interaction'],
        ];

        foreach ($screens as [$uri, $view]) {
            $response = $this->get($uri);

            $response->assertOk();
            $response->assertViewIs($view);
            $response->assertSee('css/account-dashboard.css');
            $response->assertSee('Instant Response')
                ->assertSee('href="'.route('dashboard.instant-response').'"', false);
            $response->assertSee('My Plan')
                ->assertSee('href="'.route('dashboard.my-plan').'"', false);
            if ($uri === '/dashboard') {
                $response->assertSee('Settings')
                    ->assertSee('Sign Out')
                    ->assertSee('method="post" action="'.route('logout').'"', false);
            }
        }
    }

    public function test_dashboard_badges_and_feed_use_live_unread_activity(): void
    {
        $ownerId = $this->createDashboardUser('activity-owner@example.test');
        $senderId = $this->createDashboardUser('activity-sender@example.test');
        $conversationId = DB::table('profile_contact_conversations')->insertGetId([
            'profile_type' => 'business',
            'profile_id' => 1,
            'owner_user_id' => $ownerId,
            'sender_user_id' => $senderId,
            'sender_name' => 'Activity Sender',
            'sender_email' => 'activity-sender@example.test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $messageId = DB::table('profile_contact_messages')->insertGetId([
            'conversation_id' => $conversationId,
            'sender_user_id' => $senderId,
            'message' => 'Could we discuss the opportunity?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $notificationId = (string) Str::uuid();
        DB::table('notifications')->insert([
            'id' => $notificationId,
            'type' => DashboardActivityNotification::class,
            'notifiable_type' => UserAccount::class,
            'notifiable_id' => $ownerId,
            'data' => json_encode([
                'type' => 'message',
                'title' => 'New message from Activity Sender',
                'message' => 'Could we discuss the opportunity?',
                'url' => route('dashboard.inbox.conversation', $conversationId),
                'conversation_id' => $conversationId,
            ], JSON_THROW_ON_ERROR),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs(UserAccount::query()->findOrFail($senderId))
            ->postJson(route('dashboard.notifications.read', $notificationId))
            ->assertNotFound();
        $this->actingAs(UserAccount::query()->findOrFail($ownerId))
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('data-unread-message-count', false)
            ->assertSee('>1</span>', false)
            ->assertSee('New message from Activity Sender')
            ->assertSee('Could we discuss the opportunity?');
        $this->getJson(route('dashboard.activity'))
            ->assertOk()
            ->assertJsonPath('unreadMessageCount', 1)
            ->assertJsonPath('unreadNotificationCount', 1)
            ->assertJsonPath('notifications.0.title', 'New message from Activity Sender');

        $this->getJson(route('dashboard.live-chat.messages', $conversationId))->assertOk();
        $this->getJson(route('dashboard.activity'))
            ->assertOk()
            ->assertJsonPath('unreadMessageCount', 0)
            ->assertJsonPath('unreadNotificationCount', 1);

        $this->postJson(route('dashboard.notifications.read', $notificationId))
            ->assertOk()
            ->assertJsonPath('marked_read', true);
        $this->getJson(route('dashboard.activity'))
            ->assertOk()
            ->assertJsonPath('unreadNotificationCount', 0);
        $this->assertDatabaseHas('profile_contact_conversation_reads', [
            'conversation_id' => $conversationId,
            'user_id' => $ownerId,
            'last_read_message_id' => $messageId,
        ]);
    }

    public function test_dashboard_account_menu_signs_the_user_out(): void
    {
        $userId = DB::table('user_account')->insertGetId([
            'name' => 'Dashboard Member',
            'email' => 'dashboard-menu@example.test',
            'password' => Hash::make('Password123!'),
            'company_name' => 'Dashboard Company',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs(UserAccount::query()->findOrFail($userId))
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->get(route('dashboard.index'))->assertRedirect(route('login'));
    }

    public function test_previous_dashboard_urls_redirect_to_the_new_route_structure(): void
    {
        $redirects = [
            ['/dashboard-profile', '/dashboard/profile'],
            ['/dashboard-password', '/dashboard/password'],
            ['/bx-inbox', '/dashboard/inbox'],
            ['/proposal-sent', '/dashboard/proposals/sent'],
            ['/proposal-received', '/dashboard/proposals/received'],
            ['/instant-response', '/dashboard/instant-response'],
        ];

        foreach ($redirects as [$oldUrl, $newUrl]) {
            $this->get($oldUrl)->assertRedirect($newUrl);
        }
    }

    public function test_dashboard_requires_an_authenticated_account(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get(route('dashboard.profiles.create'))->assertRedirect(route('login'));
    }

    public function test_dashboard_shows_account_contact_details_and_opens_create_profile_type_picker(): void
    {
        $userId = $this->createDashboardUser('contact-details@example.test');
        DB::table('user_account')->where('user_id', $userId)->update([
            'mobile' => '+447700900123',
            'location' => 'London',
        ]);
        $this->actingAs(UserAccount::query()->findOrFail($userId));

        $this->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('contact-details@example.test')
            ->assertSee('+447700900123')
            ->assertSee('London')
            ->assertSee('identity-contact', false)
            ->assertSee('<svg aria-hidden="true"', false)
            ->assertSee(route('dashboard.profiles.create'), false)
            ->assertSee('>(Create | Manage)</a>', false);

        DB::table('user_account')->where('user_id', $userId)->update(['location' => null]);
        $this->actingAs(UserAccount::query()->findOrFail($userId));
        $this->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('N/A');

        $this->get(route('dashboard.profiles.create'))
            ->assertOk()
            ->assertViewIs('dashboard.profile-create')
            ->assertSee('Business Profile')
            ->assertSee('Startup Profile')
            ->assertSee('Investor Profile')
            ->assertSee('Mentor Profile')
            ->assertSee(route('dashboard.profiles.create.form', 'business'), false);

        $this->get(route('dashboard.profiles.create.form', 'business'))
            ->assertOk()
            ->assertSee(route('dashboard.profiles.store', 'business'), false)
            ->assertSee('href="'.route('dashboard.profiles.create.form', 'business').'"', false)
            ->assertSee('href="'.route('dashboard.profiles.create.form', 'investor').'"', false)
            ->assertSee('href="'.route('dashboard.profiles.create.form', 'mentor').'"', false)
            ->assertSee('href="'.route('dashboard.profiles.create.form', 'startup').'"', false)
            ->assertSee('Dashboard Test User')
            ->assertSee('contact-details@example.test');
    }

    public function test_my_plan_shows_available_offers_without_an_active_membership_and_active_membership_details_when_present(): void
    {
        $userId = $this->createDashboardUser('my-plan@example.test');
        DB::table('membership_plans')->insert([
            'plan_id' => 5,
            'plan_type' => 2,
            'plan_name' => 'Premium Plan',
            'plan_desc' => 'More visibility and member benefits.',
            'profile_type' => 1,
            'profile_name' => 'Business',
            'validity_in_days' => 90,
            'plan_amount' => '29.99',
            'interaction_credits' => '50',
            'instant_responses' => '10',
            'is_active' => true,
            'deactivated_at' => now()->addYear(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs(UserAccount::query()->findOrFail($userId))
            ->get(route('dashboard.my-plan'))
            ->assertOk()
            ->assertViewIs('dashboard.my-plan')
            ->assertSee('Upgrade Plan')
            ->assertSee('Membership Details')
            ->assertSee('No active membership found.')
            ->assertSee('Free')
            ->assertSee('Premium')
            ->assertSee('Gold')
            ->assertSee('Platinum')
            ->assertSee('name="selected_plan"', false)
            ->assertSee('value="free"', false)
            ->assertSee('value="premium"', false)
            ->assertSee('value="gold"', false)
            ->assertSee('value="platinum"', false)
            ->assertSee('Promo Code:')
            ->assertSee('Credit Card')
            ->assertSee('Net Banking')
            ->assertSee('name="payment_mode"', false)
            ->assertSee('Submit');
        $this->assertSame(4, substr_count($this->get(route('dashboard.my-plan'))->getContent(), 'class="my-plan-card my-plan-card-'));

        DB::table('profile_memberships')->insert([
            'user_id' => $userId,
            'profile_type' => 1,
            'profile_id' => 1,
            'order_no' => 1001,
            'amount' => '29.99',
            'membership_type' => 5,
            'payment_source' => 1,
            'interaction_credits' => '50',
            'instant_responses' => '10',
            'activation_date' => now()->subMonth(),
            'expiry_date' => now()->addMonths(2),
            'is_active' => true,
            'upg_source' => 1,
            'created_at' => now()->subMonth(),
            'updated_at' => now(),
        ]);

        $this->get(route('dashboard.my-plan'))
            ->assertOk()
            ->assertSee('Membership Details')
            ->assertSee('Premium')
            ->assertSee('29.99')
            ->assertSee('50')
            ->assertSee('10')
            ->assertSee('Upgrade Plan')
            ->assertSee('name="selected_plan"', false);
    }

    public function test_my_plan_treats_expired_memberships_as_inactive(): void
    {
        $userId = $this->createDashboardUser('expired-plan@example.test');
        DB::table('membership_plans')->insert([
            'plan_id' => 8,
            'plan_type' => 2,
            'plan_name' => 'Gold Plan',
            'plan_desc' => 'A gold membership offer.',
            'profile_type' => 1,
            'profile_name' => 'Business',
            'validity_in_days' => 180,
            'plan_amount' => '59.99',
            'interaction_credits' => '100',
            'instant_responses' => '20',
            'is_active' => true,
            'deactivated_at' => now()->addYear(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('profile_memberships')->insert([
            'user_id' => $userId,
            'profile_type' => 1,
            'profile_id' => 1,
            'order_no' => 1002,
            'amount' => '59.99',
            'membership_type' => 8,
            'payment_source' => 1,
            'interaction_credits' => '100',
            'instant_responses' => '20',
            'activation_date' => now()->subYear(),
            'expiry_date' => now()->subDay(),
            'is_active' => true,
            'upg_source' => 1,
            'created_at' => now()->subYear(),
            'updated_at' => now(),
        ]);

        $this->actingAs(UserAccount::query()->findOrFail($userId))
            ->get(route('dashboard.my-plan'))
            ->assertOk()
            ->assertSee('Upgrade Plan')
            ->assertSee('Gold')
            ->assertSee('No active membership found.');
    }

    public function test_dashboard_lists_all_owned_profile_types_and_filters_them(): void
    {
        $userId = $this->createDashboardUser('multi-profile@example.test');
        $profiles = [
            ['profile_business', 'business_id', 'business_profile_status', 'advmt_headline', 'Business headline'],
            ['profile_investor', 'investor_id', 'inv_profile_status', 'inv_headline', 'Investor headline'],
            ['profile_mentors', 'mentor_id', 'mentor_profile_status', 'mentor_adv_headline', 'Mentor headline'],
            ['profile_startups', 'startup_id', 'startup_profile_status', 'advmt_headline', 'Startup headline'],
        ];
        foreach ($profiles as [$table, $idColumn, $statusColumn, $titleColumn, $title]) {
            DB::table($table)->insert([
                'user_id' => $userId,
                $statusColumn => 1,
                $titleColumn => $title,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->actingAs(UserAccount::query()->findOrFail($userId))
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('Business headline')
            ->assertSee('Investor headline')
            ->assertSee('Mentor headline')
            ->assertSee('Startup headline');

        $this->get(route('dashboard.index', ['type' => 'investor']))
            ->assertOk()
            ->assertSee('Investor headline')
            ->assertDontSee('Business headline')
            ->assertDontSee('Mentor headline')
            ->assertDontSee('Startup headline');
    }

    public function test_dashboard_paginates_profile_cards_ten_per_page_and_uses_manage_profile_action(): void
    {
        $userId = $this->createDashboardUser('profile-pagination@example.test');
        for ($index = 1; $index <= 12; $index++) {
            DB::table('profile_business')->insert([
                'user_id' => $userId,
                'business_profile_status' => 1,
                'advmt_headline' => 'Pagination business '.$index,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->actingAs(UserAccount::query()->findOrFail($userId));

        $firstPage = $this->get(route('dashboard.index', ['type' => 'business']));
        $firstPage->assertOk()
            ->assertSee('Pagination business 1')
            ->assertSee('Pagination business 10')
            ->assertDontSee('Pagination business 11')
            ->assertSee('Manage Profile')
            ->assertSee('page=2')
            ->assertDontSee('data-manage-menu');
        $this->assertSame(10, substr_count($firstPage->getContent(), 'data-owned-profile="business"'));

        $secondPage = $this->get(route('dashboard.index', ['type' => 'business', 'page' => 2]));
        $secondPage->assertOk()
            ->assertSee('Pagination business 11')
            ->assertSee('Pagination business 12')
            ->assertDontSee('>Pagination business 1</h2>', false);
        $this->assertSame(2, substr_count($secondPage->getContent(), 'data-owned-profile="business"'));
    }

    public function test_owned_profile_can_be_viewed_and_updated_but_other_users_profiles_are_hidden(): void
    {
        Schema::table('profile_business', function (Blueprint $table): void {
            foreach ([
                'seller_name', 'seller_designation', 'seller_email', 'seller_mobile',
                'emp_count', 'entity_type', 'business_type', 'industry_sector',
                'business_website', 'annual_sales', 'director_name', 'ofc_city',
                'ofc_country', 'seller_prof_pic',
            ] as $column) {
                $table->string($column)->nullable();
            }
            foreach (['seeking_investors', 'seeking_loan', 'seeking_accelerators', 'seeking_buyers', 'seeking_mentors'] as $column) {
                $table->boolean($column)->default(false);
            }
        });

        $ownerId = $this->createDashboardUser('profile-owner@example.test');
        $otherUserId = $this->createDashboardUser('profile-other@example.test');
        $profileId = DB::table('profile_business')->insertGetId([
            'user_id' => $ownerId,
            'business_profile_status' => 1,
            'advmt_headline' => 'Original headline',
            'seller_company' => 'Original company',
            'seller_name' => 'Profile Owner',
            'seller_designation' => 'Director',
            'seller_email' => 'profile-owner@example.test',
            'seller_mobile' => '7700900123',
            'estb_year' => 2018,
            'emp_count' => '10-50',
            'entity_type' => 'Private Limited Company',
            'business_type' => 'B2B',
            'industry_sector' => 'Retail',
            'ofc_country' => 'United Kingdom',
            'seeking_investors' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs(UserAccount::query()->findOrFail($ownerId))
            ->get(route('dashboard.profiles.show', ['type' => 'business', 'id' => $profileId]))
            ->assertOk()
            ->assertSee('Original headline')
            ->assertSee('Original company')
            ->assertSee('View Business Profile');

        $this->get(route('dashboard.profiles.edit', ['type' => 'business', 'id' => $profileId]))
            ->assertOk()
            ->assertSee('Manage Business Information')
            ->assertSee('role="tablist"', false)
            ->assertSee('Confidential Info')
            ->assertSee('Advert Details')
            ->assertSee('Business Info')
            ->assertSee('Financial Details')
            ->assertSee('Team Details')
            ->assertSee('Headquarters')
            ->assertSee('Requirements')
            ->assertSee('Attachments')
            ->assertSee('data-phone-country-code', false)
            ->assertSee('name="advmt_headline"', false)
            ->assertSee('name="seller_company"', false)
            ->assertSee('name="seeking_investors"', false)
            ->assertSee('name="business_photo_1"', false)
            ->assertSee('<option value="2018" selected>', false);

        $this->put(route('dashboard.profiles.update', ['type' => 'business', 'id' => $profileId]), [
            'advmt_headline' => 'Updated headline',
            'seller_company' => 'Updated company',
            'estb_year' => '2012',
            'seller_name' => 'Updated Profile Owner',
            'seller_designation' => 'Director',
            'seller_email' => 'profile-owner@example.test',
            'seller_mobile' => '7700900123',
            'emp_count' => '10-50',
            'entity_type' => 'Private Limited Company',
            'business_type' => 'B2B',
            'industry_sector' => 'Retail',
            'ofc_country' => 'United Kingdom',
            'seeking_buyers' => 1,
            'user_id' => $otherUserId,
            'business_profile_status' => 0,
        ])
            ->assertRedirect(route('dashboard.profiles.show', ['type' => 'business', 'id' => $profileId]))
            ->assertSessionHas('profile_record_status', 'Business profile updated successfully.');

        $this->assertDatabaseHas('profile_business', [
            'business_id' => $profileId,
            'user_id' => $ownerId,
            'business_profile_status' => 1,
            'advmt_headline' => 'Updated headline',
            'seller_company' => 'Updated company',
            'estb_year' => 2012,
            'seeking_investors' => 0,
            'seeking_buyers' => 1,
        ]);

        $this->actingAs(UserAccount::query()->findOrFail($otherUserId))
            ->get(route('dashboard.profiles.show', ['type' => 'business', 'id' => $profileId]))
            ->assertNotFound();
        $this->get(route('dashboard.profiles.edit', ['type' => 'business', 'id' => $profileId]))
            ->assertNotFound();
        $this->put(route('dashboard.profiles.update', ['type' => 'business', 'id' => $profileId]), [
            'advmt_headline' => 'Unauthorized update',
            'seller_company' => 'Unauthorized company',
        ])->assertNotFound();

        $this->assertDatabaseHas('profile_business', [
            'business_id' => $profileId,
            'user_id' => $ownerId,
            'advmt_headline' => 'Updated headline',
        ]);
    }

    public function test_profile_form_shows_all_values_from_the_authenticated_account(): void
    {
        $user = new UserAccount;
        $user->setAttribute('user_id', 1);
        $user->setAttribute('name', 'Listing Test User 01');
        $user->setAttribute('email', 'listing.test.01@example.test');
        $user->setAttribute('mobile', '07700900001');
        $user->setAttribute('location', 'London');
        $user->setAttribute('designation', 'Owner');
        $user->setAttribute('company_name', 'Listing Test Company 01');
        $user->setAttribute('reg_profile', 'business');
        $this->actingAs($user);

        $this->get(route('dashboard.profile'))
            ->assertOk()
            ->assertSee('name="name" autocomplete="name" value="Listing Test User 01"', false)
            ->assertSee('name="email" type="email" autocomplete="email" value="listing.test.01@example.test"', false)
            ->assertSee('name="phone" type="tel" autocomplete="tel" value="07700900001"', false)
            ->assertSee('data-phone-country-code', false)
            ->assertSee('UK +44')
            ->assertSee('name="location" autocomplete="address-level2" value="London"', false)
            ->assertSee('name="designation" value="Owner"', false)
            ->assertSee('name="company" value="Listing Test Company 01"', false)
            ->assertSee(route('dashboard.profile.update'), false);
    }

    public function test_profile_form_saves_updated_account_details(): void
    {
        $userId = DB::table('user_account')->insertGetId([
            'name' => 'Listing Test User 01',
            'email' => 'listing.test.01@example.test',
            'password' => 'hashed-password',
            'mobile' => '07700900001',
            'location' => 'London',
            'designation' => 'Owner',
            'company_name' => 'Listing Test Company 01',
            'is_active' => 1,
            'reg_profile' => 'business',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->actingAs(UserAccount::query()->findOrFail($userId));

        $this->put(route('dashboard.profile.update'), [
            'name' => 'Updated Test User',
            'email' => 'listing.test.01@example.test',
            'phone' => '07700111222',
            'location' => 'Manchester',
            'designation' => 'Director',
            'company' => 'Updated Test Company',
        ])
            ->assertRedirect(route('dashboard.profile'))
            ->assertSessionHas('profile_status', 'Your profile was updated successfully.');

        $this->assertDatabaseHas('user_account', [
            'user_id' => $userId,
            'name' => 'Updated Test User',
            'email' => 'listing.test.01@example.test',
            'mobile' => '07700111222',
            'location' => 'Manchester',
            'designation' => 'Director',
            'company_name' => 'Updated Test Company',
        ]);
    }

    public function test_password_form_changes_password_and_shows_success_message(): void
    {
        $oldPassword = 'OldPass123!';
        $userId = DB::table('user_account')->insertGetId([
            'name' => 'Password Test User',
            'email' => 'password-test@example.test',
            'password' => Hash::make($oldPassword),
            'company_name' => 'Password Test Company',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->actingAs(UserAccount::query()->findOrFail($userId));

        $this->put(route('dashboard.password.update'), [
            'oldPassword' => $oldPassword,
            'newPassword' => 'NewPass456!',
            'confirmPassword' => 'NewPass456!',
        ])
            ->assertRedirect(route('dashboard.password'))
            ->assertSessionHas('password_status', 'Your password was updated successfully.');

        $this->assertTrue(Hash::check(
            'NewPass456!',
            DB::table('user_account')->where('user_id', $userId)->value('password')
        ));

        $this->withSession(['password_status' => 'Your password was updated successfully.'])
            ->get(route('dashboard.password'))
            ->assertOk()
            ->assertSee('Your password was updated successfully.')
            ->assertSee('role="status"', false);
    }

    public function test_password_form_rejects_incorrect_old_password_and_keeps_password_unchanged(): void
    {
        $oldPassword = 'OldPass123!';
        $hash = Hash::make($oldPassword);
        $userId = DB::table('user_account')->insertGetId([
            'name' => 'Password Test User',
            'email' => 'password-test@example.test',
            'password' => $hash,
            'company_name' => 'Password Test Company',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->actingAs(UserAccount::query()->findOrFail($userId));

        $this->from(route('dashboard.password'))
            ->put(route('dashboard.password.update'), [
                'oldPassword' => 'WrongPass123!',
                'newPassword' => 'NewPass456!',
                'confirmPassword' => 'NewPass456!',
            ])
            ->assertRedirect(route('dashboard.password'))
            ->assertSessionHasErrors('oldPassword');

        $this->assertSame($hash, DB::table('user_account')->where('user_id', $userId)->value('password'));
    }

    public function test_password_form_rejects_weak_or_mismatched_passwords(): void
    {
        $userId = DB::table('user_account')->insertGetId([
            'name' => 'Password Test User',
            'email' => 'password-test@example.test',
            'password' => Hash::make('OldPass123!'),
            'company_name' => 'Password Test Company',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->actingAs(UserAccount::query()->findOrFail($userId));

        $this->from(route('dashboard.password'))
            ->put(route('dashboard.password.update'), [
                'oldPassword' => 'OldPass123!',
                'newPassword' => 'weak',
                'confirmPassword' => 'not-matching',
            ])
            ->assertRedirect(route('dashboard.password'))
            ->assertSessionHasErrors(['newPassword', 'confirmPassword']);
    }

    private function createNotificationsTable(): void
    {
        Schema::create('notifications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    private function createDashboardUser(string $email): int
    {
        return DB::table('user_account')->insertGetId([
            'name' => 'Dashboard Test User',
            'email' => $email,
            'password' => Hash::make('TestPass123!'),
            'company_name' => 'Dashboard Test Company',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createProfileTable(string $tableName, string $idColumn, string $statusColumn, array $fields): void
    {
        Schema::create($tableName, function (Blueprint $table) use ($idColumn, $statusColumn, $fields): void {
            $table->increments($idColumn);
            $table->unsignedInteger('user_id');
            $table->boolean($statusColumn)->default(false);
            foreach ($fields as $field) {
                if ($field === 'estb_year') {
                    $table->integer($field)->nullable();
                } else {
                    $table->string($field)->nullable();
                }
            }
            $table->timestamps();
        });
    }
}
