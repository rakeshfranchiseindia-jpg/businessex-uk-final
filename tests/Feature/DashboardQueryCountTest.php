<?php

namespace Tests\Feature;

use App\Models\UserAccount;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Guards against N+1 regressions: the number of queries a dashboard page
 * runs must not grow with the number of conversations/proposals listed.
 */
class DashboardQueryCountTest extends TestCase
{
    private int $ownerId;

    private int $conversationId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate')->assertSuccessful();

        $this->ownerId = $this->user('owner@example.org');
    }

    public function test_inbox_and_proposal_pages_do_not_run_a_query_per_row(): void
    {
        // One of each profile type, and a warm-up pass so the schema cache
        // is populated before measuring.
        $this->seedConversations(4);
        $this->queryCounts();
        $small = $this->queryCounts();

        $this->seedConversations(20);
        $large = $this->queryCounts();

        $this->assertSame($small, $large);
    }

    /**
     * @return array<string, int>
     */
    private function queryCounts(): array
    {
        $owner = UserAccount::query()->findOrFail($this->ownerId);
        $counts = [];

        foreach ([
            'inbox' => route('dashboard.inbox'),
            'received' => route('dashboard.proposals.received'),
            'sent' => route('dashboard.proposals.sent'),
            'conversation' => route('dashboard.inbox.conversation', $this->conversationId),
        ] as $page => $url) {
            $queries = 0;
            DB::listen(function () use (&$queries): void {
                $queries++;
            });

            $this->actingAs($owner)->get($url)->assertOk();

            $counts[$page] = $queries;
            DB::getEventDispatcher()->forget('Illuminate\Database\Events\QueryExecuted');
        }

        return $counts;
    }

    private function seedConversations(int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $senderId = $this->user('sender'.uniqid().'@example.org');
            $profileType = ['business', 'investor', 'mentor', 'startup'][$i % 4];

            $this->conversationId = DB::table('profile_contact_conversations')->insertGetId([
                'profile_type' => $profileType,
                'profile_id' => 100 + $i,
                'owner_user_id' => $this->ownerId,
                'sender_user_id' => $senderId,
                'sender_name' => 'Sender '.$i,
                'sender_email' => 'sender@example.org',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('profile_contact_messages')->insert([
                'conversation_id' => $this->conversationId,
                'sender_user_id' => $senderId,
                'message' => 'Hello '.$i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ([[$this->ownerId, $senderId], [$senderId, $this->ownerId]] as [$from, $to]) {
                $proposalId = DB::table('profile_proposals')->insertGetId([
                    'conversation_id' => $this->conversationId,
                    'profile_type' => $profileType,
                    'profile_id' => 100 + $i,
                    'sender_user_id' => $from,
                    'recipient_user_id' => $to,
                    'title' => 'Proposal '.$i,
                    'description' => 'Details',
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('profile_proposal_attachments')->insert([
                    'proposal_id' => $proposalId,
                    'uploaded_by_user_id' => $from,
                    'object_key' => 'proposals/'.$proposalId.'.pdf',
                    'original_name' => 'deck.pdf',
                    'mime_type' => 'application/pdf',
                    'file_size' => 100,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function user(string $email): int
    {
        return DB::table('user_account')->insertGetId([
            'name' => 'Member',
            'email' => $email,
            'company_name' => 'Company '.$email,
            'is_active' => 1,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
