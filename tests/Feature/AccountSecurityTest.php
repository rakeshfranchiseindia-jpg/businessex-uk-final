<?php

namespace Tests\Feature;

use App\Mail\ResetAccountPasswordEmail;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AccountSecurityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('user_account', function (Blueprint $table): void {
            $table->increments('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('company_name')->default('');
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table): void {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table): void {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function test_set_password_link_cannot_replace_an_existing_password(): void
    {
        $accountId = $this->account(['password' => Hash::make('Original123')]);
        $url = $this->setupUrl($accountId, 'member@example.com');

        $this->get($url)->assertRedirect(route('login'));

        $this->post($url, ['password' => 'Hijacked123', 'password_confirmation' => 'Hijacked123'])
            ->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('Original123', DB::table('user_account')->value('password')));
    }

    public function test_set_password_link_works_once_and_enforces_password_policy(): void
    {
        $accountId = $this->account(['password' => null]);
        $url = $this->setupUrl($accountId, 'member@example.com');

        $this->post($url, ['password' => 'lettersonly', 'password_confirmation' => 'lettersonly'])
            ->assertSessionHasErrors('password');

        $this->post($url, ['password' => 'FirstPass123', 'password_confirmation' => 'FirstPass123'])
            ->assertRedirect(route('login'));

        $this->post($url, ['password' => 'SecondPass123', 'password_confirmation' => 'SecondPass123']);

        $this->assertTrue(Hash::check('FirstPass123', DB::table('user_account')->value('password')));
    }

    public function test_inactive_accounts_cannot_use_setup_or_verification_links(): void
    {
        $accountId = $this->account(['password' => null, 'is_active' => 0, 'email_verified_at' => null]);

        $verifyUrl = URL::temporarySignedRoute('verification.verify', now()->addHour(), [
            'id' => $accountId,
            'hash' => sha1('member@example.com'),
        ]);

        $this->get($verifyUrl)->assertForbidden();
        $this->assertNull(DB::table('user_account')->value('email_verified_at'));
    }

    public function test_inactive_accounts_are_not_sent_reset_links(): void
    {
        Mail::fake();
        $this->account(['is_active' => 0]);

        $this->post(route('password.email'), ['email' => 'member@example.com'])->assertRedirect();

        Mail::assertNothingQueued();
    }

    public function test_password_reset_signs_the_account_out_everywhere(): void
    {
        config(['session.driver' => 'database']);
        Mail::fake();
        $accountId = $this->account(['password' => Hash::make('Original123')]);

        DB::table('sessions')->insert([
            ['id' => 'attacker-session', 'user_id' => $accountId, 'payload' => '', 'last_activity' => time()],
            ['id' => 'someone-else', 'user_id' => $accountId + 1, 'payload' => '', 'last_activity' => time()],
        ]);

        $this->post(route('password.email'), ['email' => 'member@example.com']);
        $token = Mail::queued(ResetAccountPasswordEmail::class)->first()->token;

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'member@example.com',
            'password' => 'Replacement123',
            'password_confirmation' => 'Replacement123',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseMissing('sessions', ['id' => 'attacker-session']);
        $this->assertDatabaseHas('sessions', ['id' => 'someone-else']);
    }

    private function account(array $overrides): int
    {
        return DB::table('user_account')->insertGetId($overrides + [
            'name' => 'Member',
            'email' => 'member@example.com',
            'password' => null,
            'is_active' => 1,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function setupUrl(int $accountId, string $email): string
    {
        return URL::temporarySignedRoute('password.setup', now()->addHour(), [
            'id' => $accountId,
            'hash' => sha1($email),
        ]);
    }
}
