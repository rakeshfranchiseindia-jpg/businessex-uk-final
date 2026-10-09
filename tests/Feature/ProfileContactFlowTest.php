<?php

namespace Tests\Feature;

use App\Models\UserAccount;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProfileContactFlowTest extends TestCase
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
            $table->string('company_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('reg_profile')->nullable();
            $table->timestamps();
        });
        Schema::create('profile_business', function (Blueprint $table): void {
            $table->increments('business_id');
            $table->unsignedInteger('user_id');
            $table->boolean('business_profile_status')->default(false);
            $table->string('advmt_headline')->nullable();
            $table->string('seller_company')->nullable();
            $table->string('seller_name')->nullable();
            $table->string('seller_email')->nullable();
            $table->string('seller_mobile')->nullable();
            $table->string('industry_sector')->nullable();
            $table->string('ofc_city')->nullable();
            $table->text('company_summary')->nullable();
            $table->timestamps();
        });
        Schema::create('profile_investor', function (Blueprint $table): void {
            $table->increments('investor_id');
            $table->unsignedInteger('user_id');
            $table->boolean('inv_profile_status')->default(false);
            $table->string('inv_headline')->nullable();
            $table->string('inv_name')->nullable();
            $table->timestamps();
        });
        Schema::create('profile_mentors', function (Blueprint $table): void {
            $table->increments('mentor_id');
            $table->unsignedInteger('user_id');
            $table->boolean('mentor_profile_status')->default(false);
            $table->string('mentor_adv_headline')->nullable();
            $table->string('mentor_name')->nullable();
            $table->timestamps();
        });
        Schema::create('profile_startups', function (Blueprint $table): void {
            $table->increments('startup_id');
            $table->unsignedInteger('user_id');
            $table->boolean('startup_profile_status')->default(false);
            $table->string('advmt_headline')->nullable();
            $table->string('startup_name')->nullable();
            $table->timestamps();
        });
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
        Schema::create('profile_instant_responses', function (Blueprint $table): void {
            $table->unsignedInteger('user_id')->primary();
            $table->boolean('enabled')->default(false);
            $table->string('message', 500);
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
        Schema::create('profile_proposal_attachments', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->unsignedInteger('uploaded_by_user_id');
            $table->string('disk', 32)->default('s3');
            $table->string('object_key', 1024);
            $table->string('original_name', 255);
            $table->string('mime_type', 255);
            $table->unsignedBigInteger('file_size');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('profile_proposal_attachments');
        Schema::dropIfExists('profile_proposals');
        Schema::dropIfExists('profile_instant_responses');
        Schema::dropIfExists('profile_contact_messages');
        Schema::dropIfExists('profile_contact_conversation_reads');
        Schema::dropIfExists('profile_contact_conversations');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('profile_startups');
        Schema::dropIfExists('profile_mentors');
        Schema::dropIfExists('profile_investor');
        Schema::dropIfExists('profile_business');
        Schema::dropIfExists('user_account');

        parent::tearDown();
    }

    public function test_active_profile_detail_is_public_and_hides_private_owner_contact_fields(): void
    {
        [$ownerId, $profileId] = $this->createBusinessProfile();

        $this->get(route('profile-details', ['type' => 'business', 'id' => $profileId]))
            ->assertOk()
            ->assertSee('Northstar Growth Opportunity')
            ->assertSee('A growing regional company.')
            ->assertDontSee('owner@example.test')
            ->assertDontSee('07700900000')
            ->assertSee('bx-header', false)
            ->assertSee('bx-footer', false)
            ->assertSee(route('profile-contact.start', ['type' => 'business', 'id' => $profileId]), false);
    }

    public function test_each_public_profile_type_has_a_live_detail_page(): void
    {
        $ownerId = $this->createUser('Profile Owner', 'owner@example.test', '07700900000', 'Northstar Ltd');
        $profiles = [
            ['profile_investor', 'investor_id', 'inv_profile_status', 'inv_headline', 'Investor opportunity'],
            ['profile_mentors', 'mentor_id', 'mentor_profile_status', 'mentor_adv_headline', 'Mentor opportunity'],
            ['profile_startups', 'startup_id', 'startup_profile_status', 'advmt_headline', 'Startup opportunity'],
        ];

        foreach ($profiles as [$table, $idColumn, $statusColumn, $titleColumn, $title]) {
            $profileId = DB::table($table)->insertGetId([
                'user_id' => $ownerId,
                $statusColumn => 1,
                $titleColumn => $title,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $type = match ($table) {
                'profile_investor' => 'investor',
                'profile_mentors' => 'mentor',
                default => 'startup',
            };

            $this->get(route('profile-details', ['type' => $type, 'id' => $profileId]))
                ->assertOk()
                ->assertSee($title)
                ->assertSee('bx-header', false)
                ->assertSee('bx-footer', false);
        }
    }

    public function test_sender_contacts_profile_and_owner_can_read_and_reply_in_inbox(): void
    {
        [$ownerId, $profileId] = $this->createBusinessProfile();
        $senderId = $this->createUser('Interested Member', 'member@example.test', '07700123456', 'Member Company');
        $sender = UserAccount::query()->findOrFail($senderId);

        $this->actingAs($sender)
            ->get(route('profile-details', ['type' => 'business', 'id' => $profileId]))
            ->assertOk()
            ->assertSee('value="member@example.test"', false)
            ->assertSee('value="07700123456"', false);

        $this->post(route('profile-contact.store', ['type' => 'business', 'id' => $profileId]), [
            'name' => $sender->name,
            'email' => $sender->email,
            'phone' => $sender->mobile,
            'message' => 'I would like to discuss this opportunity.',
        ])
            ->assertRedirect(route('dashboard.inbox.conversation', 1))
            ->assertSessionHas('status', 'Your message was sent to the Business profile owner.');

        $this->assertDatabaseHas('profile_contact_conversations', [
            'id' => 1,
            'profile_type' => 'business',
            'profile_id' => $profileId,
            'owner_user_id' => $ownerId,
            'sender_user_id' => $senderId,
            'sender_email' => 'member@example.test',
            'sender_phone' => '07700123456',
        ]);
        $this->assertDatabaseHas('profile_contact_messages', [
            'conversation_id' => 1,
            'sender_user_id' => $senderId,
            'message' => 'I would like to discuss this opportunity.',
        ]);
        $this->assertSame(1, DB::table('notifications')
            ->where('notifiable_type', UserAccount::class)
            ->where('notifiable_id', $ownerId)
            ->count());

        $this->actingAs(UserAccount::query()->findOrFail($ownerId))
            ->get(route('dashboard.inbox'))
            ->assertOk()
            ->assertSee('Interested Member')
            ->assertSee('Northstar Growth Opportunity')
            ->assertSee('I would like to discuss this opportunity.');

        $this->post(route('dashboard.inbox.reply', 1), ['message' => 'Please share your availability.'])
            ->assertRedirect(route('dashboard.inbox.conversation', 1))
            ->assertSessionHas('status', 'Your reply was sent.');
        $this->assertSame(1, DB::table('notifications')
            ->where('notifiable_type', UserAccount::class)
            ->where('notifiable_id', $senderId)
            ->count());
        $this->get(route('dashboard.inbox.conversation', 1))
            ->assertOk()
            ->assertSee('I would like to discuss this opportunity.')
            ->assertSee('Please share your availability.');

        $this->actingAs($sender)
            ->get(route('dashboard.inbox.conversation', 1))
            ->assertOk()
            ->assertSee('Please share your availability.');

        $strangerId = $this->createUser('Unrelated Member', 'stranger@example.test', '07700999999', 'Other Company');
        $this->actingAs(UserAccount::query()->findOrFail($strangerId))
            ->get(route('dashboard.inbox.conversation', 1))
            ->assertNotFound();
    }

    public function test_enabled_instant_response_is_added_to_new_contact_conversation_for_sender(): void
    {
        [$ownerId, $profileId] = $this->createBusinessProfile();
        $senderId = $this->createUser('Interested Member', 'member@example.test', '07700123456', 'Member Company');
        $owner = UserAccount::query()->findOrFail($ownerId);
        $reply = 'Thanks for contacting Northstar. We will reply shortly.';

        $this->actingAs($owner)
            ->post(route('dashboard.instant-response.save'), [
                'enabled' => '1',
                'message' => $reply,
            ])
            ->assertRedirect(route('dashboard.instant-response'))
            ->assertSessionHas('status', 'Your instant response settings were saved.');

        $this->get(route('dashboard.instant-response'))
            ->assertOk()
            ->assertSee('checked', false)
            ->assertSee($reply);

        $sender = UserAccount::query()->findOrFail($senderId);
        $this->actingAs($sender)
            ->post(route('profile-contact.store', ['type' => 'business', 'id' => $profileId]), [
                'name' => $sender->name,
                'email' => $sender->email,
                'phone' => $sender->mobile,
                'message' => 'I would like to learn more.',
            ])
            ->assertRedirect(route('dashboard.inbox.conversation', 1));

        $this->assertDatabaseHas('profile_contact_messages', [
            'conversation_id' => 1,
            'sender_user_id' => $ownerId,
            'message' => $reply,
        ]);
        $this->assertSame(1, DB::table('notifications')
            ->where('notifiable_type', UserAccount::class)
            ->where('notifiable_id', $senderId)
            ->count());
        $this->get(route('dashboard.inbox.conversation', 1))
            ->assertOk()
            ->assertSee('I would like to learn more.')
            ->assertSee($reply);
    }

    public function test_disabled_instant_response_is_not_sent_to_contacting_member(): void
    {
        [$ownerId, $profileId] = $this->createBusinessProfile();
        $senderId = $this->createUser('Interested Member', 'member@example.test', '07700123456', 'Member Company');
        DB::table('profile_instant_responses')->insert([
            'user_id' => $ownerId,
            'enabled' => false,
            'message' => 'This should not be sent.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $sender = UserAccount::query()->findOrFail($senderId);

        $this->actingAs($sender)
            ->post(route('profile-contact.store', ['type' => 'business', 'id' => $profileId]), [
                'name' => $sender->name,
                'email' => $sender->email,
                'message' => 'I would like to learn more.',
            ])
            ->assertRedirect(route('dashboard.inbox.conversation', 1));

        $this->assertDatabaseCount('profile_contact_messages', 1);
        $this->assertDatabaseMissing('profile_contact_messages', [
            'conversation_id' => 1,
            'message' => 'This should not be sent.',
        ]);
    }

    public function test_contact_requires_sign_in_and_users_cannot_message_their_own_profile(): void
    {
        [$ownerId, $profileId] = $this->createBusinessProfile();

        $contactStartUrl = route('profile-contact.start', ['type' => 'business', 'id' => $profileId]);
        $this->get($contactStartUrl)
            ->assertRedirect(route('login'))
            ->assertSessionHas('url.intended', $contactStartUrl);

        $this->post(route('profile-contact.store', ['type' => 'business', 'id' => $profileId]), [])
            ->assertRedirect(route('login'));

        $senderId = $this->createUser('Interested Member', 'member@example.test', '07700123456', 'Member Company');
        $this->actingAs(UserAccount::query()->findOrFail($senderId))
            ->get($contactStartUrl)
            ->assertRedirect(route('profile-details', ['type' => 'business', 'id' => $profileId]))
            ->assertSessionHas('open_contact', true);

        $this->actingAs(UserAccount::query()->findOrFail($ownerId))
            ->get(route('profile-details', ['type' => 'business', 'id' => $profileId]))
            ->assertOk()
            ->assertSee('You cannot send a contact request to yourself.');

        $this->post(route('profile-contact.store', ['type' => 'business', 'id' => $profileId]), [
            'name' => 'Owner',
            'email' => 'owner@example.test',
            'message' => 'Self-message',
        ])->assertForbidden();

        $this->assertDatabaseCount('profile_contact_conversations', 0);
    }

    public function test_profile_owner_sends_proposal_and_recipient_can_respond_with_status(): void
    {
        [$ownerId, $profileId] = $this->createBusinessProfile();
        $recipientId = $this->createUser('Interested Member', 'member@example.test', '07700123456', 'Member Company');
        $conversationId = DB::table('profile_contact_conversations')->insertGetId([
            'profile_type' => 'business',
            'profile_id' => $profileId,
            'owner_user_id' => $ownerId,
            'sender_user_id' => $recipientId,
            'sender_name' => 'Interested Member',
            'sender_email' => 'member@example.test',
            'sender_phone' => '07700123456',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $owner = UserAccount::query()->findOrFail($ownerId);

        $this->actingAs($owner)
            ->post(route('dashboard.inbox.proposals.store', $conversationId), [
                'title' => 'Strategic acquisition proposal',
                'description' => 'We would like to acquire the business subject to due diligence.',
                'amount' => '250000.00',
            ])
            ->assertRedirect(route('dashboard.inbox.conversation', $conversationId))
            ->assertSessionHas('status', 'Your proposal was sent to the interested member.');

        $this->assertDatabaseHas('profile_proposals', [
            'conversation_id' => $conversationId,
            'profile_type' => 'business',
            'profile_id' => $profileId,
            'sender_user_id' => $ownerId,
            'recipient_user_id' => $recipientId,
            'title' => 'Strategic acquisition proposal',
            'amount' => '250000.00',
            'status' => 'pending',
        ]);
        $this->assertSame(1, DB::table('notifications')
            ->where('notifiable_type', UserAccount::class)
            ->where('notifiable_id', $recipientId)
            ->count());

        $this->get(route('dashboard.proposals.sent'))
            ->assertOk()
            ->assertSee('Strategic acquisition proposal')
            ->assertSee('Northstar Growth Opportunity')
            ->assertSee('Member Company')
            ->assertSee('Pending')
            ->assertSee('£250,000.00');

        $this->actingAs(UserAccount::query()->findOrFail($recipientId))
            ->get(route('dashboard.proposals.received', ['status' => 'pending']))
            ->assertOk()
            ->assertSee('Strategic acquisition proposal')
            ->assertSee('Northstar Growth Opportunity')
            ->assertSee('Northstar Ltd')
            ->assertSee('Pending');

        $this->put(route('dashboard.proposals.status', 1), ['status' => 'accepted'])
            ->assertRedirect(route('dashboard.inbox.conversation', $conversationId))
            ->assertSessionHas('status', 'Proposal accepted.');
        $this->assertDatabaseHas('profile_proposals', [
            'id' => 1,
            'status' => 'accepted',
        ]);
        $this->assertSame(1, DB::table('notifications')
            ->where('notifiable_type', UserAccount::class)
            ->where('notifiable_id', $ownerId)
            ->count());

        $this->get(route('dashboard.proposals.received', ['status' => 'accepted']))
            ->assertOk()
            ->assertSee('Strategic acquisition proposal')
            ->assertSee('Accepted');
        $this->get(route('dashboard.proposals.received', ['status' => 'declined']))
            ->assertOk()
            ->assertDontSee('Strategic acquisition proposal');

        $this->actingAs($owner)
            ->get(route('dashboard.inbox.conversation', $conversationId))
            ->assertOk()
            ->assertSee('Strategic acquisition proposal')
            ->assertSee('Accepted');
    }

    public function test_owner_can_select_interested_member_and_profile_and_send_private_attachments(): void
    {
        config([
            'filesystems.disks.s3.key' => 'test-access-key',
            'filesystems.disks.s3.secret' => 'test-secret-key',
        ]);
        Storage::fake('s3');
        [$ownerId, $profileId] = $this->createBusinessProfile();
        $recipientId = $this->createUser('Interested Member', 'member@example.test', '07700123456', 'Member Company');
        $conversationId = DB::table('profile_contact_conversations')->insertGetId([
            'profile_type' => 'business',
            'profile_id' => $profileId,
            'owner_user_id' => $ownerId,
            'sender_user_id' => $recipientId,
            'sender_name' => 'Interested Member',
            'sender_email' => 'member@example.test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $owner = UserAccount::query()->findOrFail($ownerId);

        $this->actingAs($owner)
            ->get(route('dashboard.proposals.sent'))
            ->assertOk()
            ->assertSee('Send a proposal')
            ->assertSee('Member Company')
            ->assertSee('Northstar Growth Opportunity');

        $this->post(route('dashboard.proposals.store'), [
            'profile' => 'business:' . $profileId,
            'recipient_user_id' => $recipientId,
            'title' => 'Acquisition proposal with terms',
            'description' => 'Please review the attached terms.',
            'amount' => '300000',
            'attachments' => [UploadedFile::fake()->create('terms.pdf', 20, 'application/pdf')],
        ])
            ->assertRedirect(route('dashboard.inbox.conversation', $conversationId))
            ->assertSessionHas('status', 'Your proposal was sent to the selected interested member.');

        $proposalId = DB::table('profile_proposals')->value('id');
        $attachment = DB::table('profile_proposal_attachments')->where('proposal_id', $proposalId)->first();
        $this->assertNotNull($attachment);
        $this->assertSame('terms.pdf', $attachment->original_name);
        $this->assertSame((string) $ownerId, explode('/', $attachment->object_key)[1]);
        Storage::disk('s3')->assertExists($attachment->object_key);

        $this->actingAs($owner)
            ->get(route('dashboard.proposals.sent'))
            ->assertOk()
            ->assertSee('terms.pdf');
        $this->actingAs(UserAccount::query()->findOrFail($recipientId))
            ->get(route('dashboard.inbox.conversation', $conversationId))
            ->assertOk()
            ->assertSee('terms.pdf');

        $outsiderId = $this->createUser('Unrelated Member', 'outsider@example.test', '07700888888', 'Unrelated Company');
        $this->actingAs(UserAccount::query()->findOrFail($outsiderId))
            ->get(route('dashboard.proposals.attachments.download', [$proposalId, $attachment->id]))
            ->assertNotFound();
    }

    public function test_proposal_attachment_upload_fails_with_actionable_error_when_s3_credentials_are_missing(): void
    {
        config([
            'filesystems.disks.s3.key' => null,
            'filesystems.disks.s3.secret' => null,
        ]);
        Storage::fake('s3');
        [$ownerId, $profileId] = $this->createBusinessProfile();
        $recipientId = $this->createUser('Interested Member', 'member@example.test', '07700123456', 'Member Company');
        DB::table('profile_contact_conversations')->insert([
            'profile_type' => 'business',
            'profile_id' => $profileId,
            'owner_user_id' => $ownerId,
            'sender_user_id' => $recipientId,
            'sender_name' => 'Interested Member',
            'sender_email' => 'member@example.test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs(UserAccount::query()->findOrFail($ownerId))
            ->from(route('dashboard.proposals.sent'))
            ->post(route('dashboard.proposals.store'), [
                'profile' => 'business:' . $profileId,
                'recipient_user_id' => $recipientId,
                'title' => 'Proposal requiring an attachment',
                'description' => 'Please review the attached terms.',
                'attachments' => [UploadedFile::fake()->create('terms.pdf', 20, 'application/pdf')],
            ])
            ->assertRedirect(route('dashboard.proposals.sent'))
            ->assertSessionHasErrors([
                'attachments' => 'Proposal attachments cannot be uploaded because AWS_ACCESS_KEY_ID or AWS_SECRET_ACCESS_KEY is missing from the application environment. Add valid IAM credentials and clear the configuration cache.',
            ]);

        $this->assertDatabaseCount('profile_proposals', 0);
        $this->assertDatabaseCount('profile_proposal_attachments', 0);
    }

    public function test_proposal_composer_remains_visible_when_profile_has_no_interested_members(): void
    {
        [$ownerId] = $this->createBusinessProfile();

        $this->actingAs(UserAccount::query()->findOrFail($ownerId))
            ->get(route('dashboard.proposals.sent'))
            ->assertOk()
            ->assertSee('Send a proposal')
            ->assertSee('Northstar Growth Opportunity')
            ->assertSee('No interested members yet')
            ->assertSee('Members will appear here after they contact one of your profiles.')
            ->assertSee('Proposal title')
            ->assertSee('Proposal details')
            ->assertSee('name="recipient_user_id" required disabled', false)
            ->assertSee('type="submit" disabled', false);
    }

    public function test_selected_profile_proposal_rejects_unrelated_users_and_profiles(): void
    {
        [$ownerId, $profileId] = $this->createBusinessProfile();
        $recipientId = $this->createUser('Interested Member', 'member@example.test', '07700123456', 'Member Company');
        $otherOwnerId = $this->createUser('Other Owner', 'other@example.test', '07700999999', 'Other Company');
        $otherProfileId = DB::table('profile_business')->insertGetId([
            'user_id' => $otherOwnerId,
            'business_profile_status' => 1,
            'advmt_headline' => 'Other opportunity',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('profile_contact_conversations')->insert([
            'profile_type' => 'business',
            'profile_id' => $profileId,
            'owner_user_id' => $ownerId,
            'sender_user_id' => $recipientId,
            'sender_name' => 'Interested Member',
            'sender_email' => 'member@example.test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs(UserAccount::query()->findOrFail($ownerId))
            ->post(route('dashboard.proposals.store'), [
                'profile' => 'business:' . $otherProfileId,
                'recipient_user_id' => $recipientId,
                'title' => 'Unauthorized profile proposal',
                'description' => 'This must not be sent.',
            ])
            ->assertNotFound();
        $this->post(route('dashboard.proposals.store'), [
            'profile' => 'business:' . $profileId,
            'recipient_user_id' => $otherOwnerId,
            'title' => 'Uninterested recipient proposal',
            'description' => 'This must not be sent.',
        ])->assertNotFound();

        $this->assertDatabaseCount('profile_proposals', 0);
    }

    public function test_only_profile_owner_can_send_a_proposal_and_only_recipient_can_respond(): void
    {
        [$ownerId, $profileId] = $this->createBusinessProfile();
        $recipientId = $this->createUser('Interested Member', 'member@example.test', '07700123456', 'Member Company');
        $conversationId = DB::table('profile_contact_conversations')->insertGetId([
            'profile_type' => 'business',
            'profile_id' => $profileId,
            'owner_user_id' => $ownerId,
            'sender_user_id' => $recipientId,
            'sender_name' => 'Interested Member',
            'sender_email' => 'member@example.test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $proposalId = DB::table('profile_proposals')->insertGetId([
            'conversation_id' => $conversationId,
            'profile_type' => 'business',
            'profile_id' => $profileId,
            'sender_user_id' => $ownerId,
            'recipient_user_id' => $recipientId,
            'title' => 'Pending proposal',
            'description' => 'Proposal details',
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs(UserAccount::query()->findOrFail($recipientId))
            ->post(route('dashboard.inbox.proposals.store', $conversationId), [
                'title' => 'Unauthorized proposal',
                'description' => 'Should not be sent',
            ])
            ->assertNotFound();
        $this->actingAs(UserAccount::query()->findOrFail($ownerId))
            ->put(route('dashboard.proposals.status', $proposalId), ['status' => 'declined'])
            ->assertNotFound();
        $this->actingAs(UserAccount::query()->findOrFail($recipientId))
            ->put(route('dashboard.proposals.status', $proposalId), ['status' => 'declined'])
            ->assertRedirect(route('dashboard.inbox.conversation', $conversationId));

        $this->assertDatabaseHas('profile_proposals', [
            'id' => $proposalId,
            'status' => 'declined',
        ]);
    }

    private function createBusinessProfile(): array
    {
        $ownerId = $this->createUser('Profile Owner', 'owner@example.test', '07700900000', 'Northstar Ltd');
        $profileId = DB::table('profile_business')->insertGetId([
            'user_id' => $ownerId,
            'business_profile_status' => 1,
            'advmt_headline' => 'Northstar Growth Opportunity',
            'seller_company' => 'Northstar Ltd',
            'seller_name' => 'Profile Owner',
            'seller_email' => 'owner@example.test',
            'seller_mobile' => '07700900000',
            'industry_sector' => 'Technology',
            'ofc_city' => 'Bristol',
            'company_summary' => 'A growing regional company.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [$ownerId, $profileId];
    }

    private function createUser(string $name, string $email, string $phone, string $company): int
    {
        return DB::table('user_account')->insertGetId([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('TestPass123!'),
            'mobile' => $phone,
            'company_name' => $company,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
