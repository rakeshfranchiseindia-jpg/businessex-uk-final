<?php

namespace Tests\Feature;

use App\Mail\NewsletterSubscriptionConfirmation;
use App\Mail\NewsletterVerificationEmail;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
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

        // SubscribeController only accepts cities from bx_cities.
        Schema::create('bx_cities', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('city', 55);
            $table->string('state', 15)->default('');
            $table->integer('country')->default(5);
            $table->timestamps();
        });
        DB::table('bx_cities')->insert(['city' => 'London', 'state' => 'England']);
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('bx_cities');
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
            ->assertRedirect(route('home').'#newsletter-subscription')
            ->assertSessionHas('newsletter_status');

        $this->assertDatabaseHas('businessex_newsletter', [
            'user_id' => 0,
            'name' => 'Newsletter Reader',
            'email' => 'newsletter@example.com',
            'phone' => '+44 1234 567890',
            'city' => 'London',
            'status' => 'P',
            'unsubscribe_reason' => null,
        ]);
        Mail::assertQueued(
            NewsletterVerificationEmail::class,
            fn (NewsletterVerificationEmail $mail): bool => $mail->hasTo('newsletter@example.com')
                && $mail->name === 'Newsletter Reader'
        );
        Mail::assertNotQueued(NewsletterSubscriptionConfirmation::class);
    }

    public function test_verification_link_confirms_subscription_once(): void
    {
        Mail::fake();

        $this->post(route('newsletter.subscribe'), [
            'name' => 'Newsletter Reader',
            'email' => 'reader@example.com',
            'phone' => '+44 1234 567890',
            'city' => 'London',
        ]);

        $verifyUrl = Mail::queued(NewsletterVerificationEmail::class)->first()->verifyUrl;

        $this->get($verifyUrl.'&tampered=1')->assertForbidden();

        $this->get($verifyUrl)
            ->assertRedirect(route('home').'#newsletter-subscription')
            ->assertSessionHas('newsletter_status');
        $this->get($verifyUrl)->assertRedirect();

        $this->assertDatabaseHas('businessex_newsletter', ['email' => 'reader@example.com', 'status' => 'S']);
        Mail::assertQueued(NewsletterSubscriptionConfirmation::class, 1);
        Mail::assertQueued(
            NewsletterSubscriptionConfirmation::class,
            fn (NewsletterSubscriptionConfirmation $mail): bool => $mail->hasTo('reader@example.com')
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
            ->assertRedirect(route('home').'#newsletter-subscription');

        $this->assertDatabaseCount('businessex_newsletter', 1);
        $this->assertDatabaseHas('businessex_newsletter', [
            'user_id' => 12,
            'name' => 'Returning Reader',
            'email' => 'reader@example.com',
            'phone' => '+44 1111 222333',
            'city' => 'London',
            'status' => 'P',
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

    public function test_unknown_city_is_rejected(): void
    {
        $this->from(route('home'))
            ->post(route('newsletter.subscribe'), [
                'name' => 'Reader',
                'email' => 'reader@example.com',
                'phone' => '+44 1111 222333',
                'city' => 'Atlantis',
            ])
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors('city');

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
            ->assertSee('Please check your email and click the confirmation link to complete your subscription.');
    }
}
