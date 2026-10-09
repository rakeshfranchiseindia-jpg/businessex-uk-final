<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ChatbotVisitorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('chatbot_knowledge_base', function (Blueprint $table): void {
            $table->id();
            $table->string('intent', 100)->unique();
            $table->string('keywords', 1000);
            $table->text('answer');
            $table->string('answer_url', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        Schema::create('chatbot_leads', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('mobile', 32);
            $table->text('message');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('chatbot_leads');
        Schema::dropIfExists('chatbot_knowledge_base');

        parent::tearDown();
    }

    public function test_homepage_includes_the_visitor_chat_widget(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('BusinessX Assistant')
            ->assertSee('Chat with us')
            ->assertSee(route('chatbot.respond'))
            ->assertSee(route('chatbot.leads.store'))
            ->assertSee('Privacy Policy');
    }

    public function test_visitor_question_matches_active_database_knowledge(): void
    {
        DB::table('chatbot_knowledge_base')->insert([
            'intent' => 'investor-listing',
            'keywords' => json_encode(['investor', 'funding']),
            'answer' => 'Browse investor listings.',
            'answer_url' => '/investor-listing',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->postJson(route('chatbot.respond'), ['message' => 'How can I find an investor?'])
            ->assertOk()
            ->assertExactJson([
                'answer' => 'Browse investor listings.',
                'link_url' => '/investor-listing',
                'matched' => true,
            ]);
    }

    public function test_unknown_question_returns_contact_fallback(): void
    {
        $this->postJson(route('chatbot.respond'), ['message' => 'What is your office parking policy?'])
            ->assertOk()
            ->assertJsonPath('matched', false)
            ->assertJsonPath('answer', 'I could not find a direct answer. Leave your contact details and our team can follow up with you.');
    }

    public function test_visitor_can_submit_lead_and_question(): void
    {
        $this->postJson(route('chatbot.leads.store'), [
            'name' => '  Visitor Name ',
            'email' => '  VISITOR@example.test ',
            'mobile' => '+44 7700 900123',
            'message' => 'I need help finding an investor.',
        ])
            ->assertCreated()
            ->assertJsonPath('message', 'Thanks — your details have been sent to our team. We will be in touch.');

        $this->assertDatabaseHas('chatbot_leads', [
            'name' => 'Visitor Name',
            'email' => 'visitor@example.test',
            'mobile' => '+44 7700 900123',
            'message' => 'I need help finding an investor.',
        ]);
    }

    public function test_chatbot_lead_requires_name_email_mobile_and_question(): void
    {
        $this->postJson(route('chatbot.leads.store'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'mobile', 'message']);

        $this->assertDatabaseCount('chatbot_leads', 0);
    }
}
