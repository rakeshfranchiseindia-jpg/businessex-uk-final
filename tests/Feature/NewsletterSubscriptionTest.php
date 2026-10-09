<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use App\Mail\NewsletterSubscriptionConfirmation;
use Tests\TestCase;

class NewsletterSubscriptionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('businessex_newsletter', function (Blueprint $table): void {
            $table->increments('newsletter_id');
            $table->unsignedInteger('user_id');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('phone', 32)->nullable();
            $table->string('city', 100)->nullable();
            $table->char('status', 1)->default('P');
            $table->string('unsubscribe_reason')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('businessex_newsletter');

        parent::tearDown();
    }

    public function test_guest_can_subscribe_and_email_is_normalized(): void
    {
        Mail::fake();

        $this->from(route('home'))
            ->post(route('newsletter.subscribe'), [
                'name' => '  Newsletter Reader ',
                'email' => '  NEWSLETTER@Example.com ',
                'phone' => '+44 1234 567890',
                'city' => 'London',
            ])
            ->assertRedirect(route('home') . '#newsletter-subscription')
            ->assertSessionHas('newsletter_status');

        $this->assertDatabaseHas('businessex_newsletter', [
            'user_id' => 0,
            'name' => 'Newsletter Reader',
            'email' => 'newsletter@example.com',
            'phone' => '+44 1234 567890',
            'city' => 'London',
            'status' => 'S',
            'unsubscribe_reason' => null,
        ]);
        Mail::assertQueued(
            NewsletterSubscriptionConfirmation::class,
            fn (NewsletterSubscriptionConfirmation $mail): bool =>
                $mail->hasTo('newsletter@example.com')
                && $mail->name === 'Newsletter Reader'
                && str_contains($mail->render(), 'Thanks for subscribing, Newsletter Reader')
        );
    }

    public function test_existing_unsubscribed_email_is_resubscribed_without_duplicate_row(): void
    {
        Mail::fake();

        DB::table('businessex_newsletter')->insert([
            'user_id' => 12,
            'email' => 'reader@example.com',
            'status' => 'U',
            'unsubscribe_reason' => 'Previously opted out',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        $this->from(route('home'))
            ->post(route('newsletter.subscribe'), [
                'name' => 'Returning Reader',
                'email' => 'READER@example.com',
                'phone' => '+44 1111 222333',
                'city' => 'London',
            ])
            ->assertRedirect(route('home') . '#newsletter-subscription');

        $this->assertDatabaseCount('businessex_newsletter', 1);
        $this->assertDatabaseHas('businessex_newsletter', [
            'user_id' => 12,
            'name' => 'Returning Reader',
            'email' => 'reader@example.com',
            'phone' => '+44 1111 222333',
            'city' => 'London',
            'status' => 'S',
            'unsubscribe_reason' => null,
        ]);
    }

    public function test_invalid_email_is_rejected_without_creating_a_subscription(): void
    {
        $this->from(route('home'))
            ->post(route('newsletter.subscribe'), [
                'name' => 'Reader',
                'email' => 'not-an-email',
                'phone' => '+44 1111 222333',
                'city' => 'London',
            ])
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('email');

        $this->assertDatabaseCount('businessex_newsletter', 0);
    }

    public function test_all_newsletter_contact_fields_are_required(): void
    {
        $this->from(route('home'))
            ->post(route('newsletter.subscribe'), ['email' => 'reader@example.com'])
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors(['name', 'phone', 'city']);

        $this->assertDatabaseCount('businessex_newsletter', 0);
    }

    public function test_homepage_newsletter_form_posts_to_subscription_route(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('newsletter.subscribe'))
            ->assertSee('id="newsletter-subscription"', false)
            ->assertSee('name="name"', false)
            ->assertSee('name="email"', false)
            ->assertSee('name="phone"', false)
            ->assertSee('data-phone-country-code', false)
            ->assertSee('UK +44')
            ->assertSee('name="city"', false);
    }

    public function test_success_message_is_rendered_in_the_newsletter_section_after_subscribing(): void
    {
        Mail::fake();

        $this->followingRedirects()
            ->post(route('newsletter.subscribe'), [
                'name' => 'Newsletter Reader',
                'email' => 'reader@example.com',
                'phone' => '+44 1234 567890',
                'city' => 'London',
            ])
            ->assertOk()
            ->assertSee('id="newsletter-subscription"', false)
            ->assertSee('class="news-feedback news-feedback-success" role="status"', false)
            ->assertSee('You are subscribed to the BusinessX newsletter. Please check your email for confirmation.');
    }
}
