<?php

namespace App\Http\Controllers;

use App\Mail\VerifyAccountEmail;
use App\Models\UserAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Throwable;

class EmailVerificationController extends Controller
{
    public function verify(Request $request, int $id, string $hash): RedirectResponse
    {
        $account = UserAccount::query()->findOrFail($id);
        abort_unless(hash_equals(sha1($account->email), $hash), 403);

        if (!$account->hasVerifiedEmail()) {
            $account->forceFill([
                'email_verified_at' => now(),
            ])->save();
        }

        if (empty($account->password)) {
            return redirect()->to(URL::temporarySignedRoute(
                'password.setup',
                now()->addMinutes(60),
                ['id' => $account->user_id, 'hash' => sha1($account->email)]
            ));
        }

        return redirect()->route('login')->with(
            'status',
            'Your email address is verified. You can now sign in.'
        );
    }

    public function resend(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);
        $email = mb_strtolower(trim($validated['email']));
        $throttleKey = 'verification-resend|' . $email . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            throw ValidationException::withMessages([
                'verification_email' => 'Please wait before requesting another verification email.',
            ]);
        }

        RateLimiter::hit($throttleKey, 3600);
        $account = UserAccount::query()->where('email', $email)->first();

        if ($account && !$account->hasVerifiedEmail()) {
            try {
                Mail::to($account->email)->queue(new VerifyAccountEmail($account));
            } catch (Throwable $exception) {
                Log::error('BusinessX account verification email could not be queued.', [
                    'user_id' => $account->user_id,
                    'exception' => $exception,
                ]);

                return redirect()->route('login')->with(
                    'verification_error',
                    'We could not queue the verification email right now. Please try again later.'
                );
            }
        }

        return redirect()->route('login')->with(
            'verification_notice',
            'If that address belongs to an unverified account, a new verification email has been queued for delivery.'
        );
    }
}
