<?php

namespace Tests\Feature;

use App\Events\ConversationMessageSent;
use App\Models\UserAccount;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LiveConversationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('user_account', function (Blueprint $table): void {
            $table->increments('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('company_name');
            $table->boolean('is_active')->default(true);
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
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('profile_contact_messages');
        Schema::dropIfExists('profile_contact_conversation_reads');
        Schema::dropIfExists('profile_contact_conversations');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('user_account');

        parent::tearDown();
    }

    public function test_conversation_reply_is_persisted_and_broadcast_with_message_data(): void
    {
        Event::fake();
        [$owner, $sender, $conversationId] = $this->makeConversation();

        $this->actingAs($owner)
            ->postJson(route('dashboard.inbox.reply', $conversationId), [
                'message' => 'Let us discuss the opportunity.',
            ])
            ->assertCreated()
            ->assertJsonPath('message.conversation_id', $conversationId)
            ->assertJsonPath('message.sender_user_id', $owner->user_id)
            ->assertJsonPath('message.message', 'Let us discuss the opportunity.');

        $this->assertDatabaseHas('profile_contact_messages', [
            'conversation_id' => $conversationId,
            'sender_user_id' => $owner->user_id,
            'message' => 'Let us discuss the opportunity.',
        ]);
        Event::assertDispatched(
            ConversationMessageSent::class,
            fn (ConversationMessageSent $event): bool => $event->conversationId === $conversationId
                && $event->senderUserId === $owner->user_id
                && $event->message === 'Let us discuss the opportunity.'
        );
    }

    public function test_only_existing_conversation_participants_can_subscribe_to_its_private_channel(): void
    {
        config([
            'broadcasting.default' => 'reverb',
            'broadcasting.connections.reverb.key' => 'local-key',
            'broadcasting.connections.reverb.secret' => 'local-secret',
            'broadcasting.connections.reverb.app_id' => 'local-app',
        ]);
        [$owner, $sender, $conversationId] = $this->makeConversation();
        $outsider = $this->makeUser('Outside User', 'outside@example.test');
        $channel = 'private-conversation.'.$conversationId;
        $this->assertDatabaseHas('profile_contact_conversations', [
            'id' => $conversationId,
            'owner_user_id' => $owner->user_id,
            'sender_user_id' => $sender->user_id,
        ]);
        require base_path('routes/channels.php');

        $ownerResponse = $this->actingAs($owner)
            ->postJson('/broadcasting/auth', [
                'socket_id' => '12345.67890',
                'channel_name' => $channel,
            ]);
        $ownerResponse->assertOk();

        $this->actingAs($sender)
            ->postJson('/broadcasting/auth', [
                'socket_id' => '12345.67890',
                'channel_name' => $channel,
            ])
            ->assertOk();

        $this->actingAs($outsider)
            ->postJson('/broadcasting/auth', [
                'socket_id' => '12345.67890',
                'channel_name' => $channel,
            ])
            ->assertForbidden();
    }

    public function test_dashboard_live_chat_lists_participant_conversations_and_messages_only(): void
    {
        [$owner, $sender, $conversationId] = $this->makeConversation();
        DB::table('profile_contact_messages')->insert([
            'conversation_id' => $conversationId,
            'sender_user_id' => $sender->user_id,
            'message' => 'Can we discuss this?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $outsider = $this->makeUser('Outside User', 'outside@example.test');

        $this->actingAs($owner)
            ->getJson(route('dashboard.live-chat.conversations'))
            ->assertOk()
            ->assertJsonPath('conversations.0.id', $conversationId)
            ->assertJsonPath('conversations.0.counterpart', 'Interested Member')
            ->assertJsonPath('conversations.0.last_message', 'Can we discuss this?');

        $this->actingAs($sender)
            ->getJson(route('dashboard.live-chat.messages', $conversationId))
            ->assertOk()
            ->assertJsonPath('messages.0.sender_name', 'Interested Member')
            ->assertJsonPath('messages.0.message', 'Can we discuss this?');

        $this->actingAs($outsider)
            ->getJson(route('dashboard.live-chat.messages', $conversationId))
            ->assertNotFound();
    }

    private function makeConversation(): array
    {
        $owner = $this->makeUser('Profile Owner', 'owner@example.test');
        $sender = $this->makeUser('Interested Member', 'member@example.test');
        $conversationId = DB::table('profile_contact_conversations')->insertGetId([
            'profile_type' => 'business',
            'profile_id' => 1,
            'owner_user_id' => $owner->user_id,
            'sender_user_id' => $sender->user_id,
            'sender_name' => $sender->name,
            'sender_email' => $sender->email,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [$owner, $sender, (int) $conversationId];
    }

    private function makeUser(string $name, string $email): UserAccount
    {
        $id = DB::table('user_account')->insertGetId([
            'name' => $name,
            'email' => $email,
            'company_name' => $name.' Company',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return UserAccount::query()->findOrFail($id);
    }
}
