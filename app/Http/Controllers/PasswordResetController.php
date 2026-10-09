<?php

namespace App\Http\Controllers;

use App\Models\UserAccount;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function forgotForm(): View
    {
        return view('dashboard.forgot-password', [
            'page' => 'home',
            'showHeader' => false,
            'showFooter' => false,
        ]);
    }

    public function resetForm(Request $request, string $token): View
    {
        return view('pages.reset-password', [
            'page' => 'home',
            'showHeader' => false,
            'showFooter' => false,
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function setupForm(Request $request, int $id, string $hash): View
    {
        $account = UserAccount::query()->findOrFail($id);
        abort_unless(hash_equals(sha1($account->email), $hash), 403);
        abort_unless($account->hasVerifiedEmail(), 403);

        return view('pages.reset-password', [
            'page' => 'home',
            'showHeader' => false,
            'showFooter' => false,
            'token' => null,
            'email' => $account->email,
            'setup' => true,
        ]);
    }

    public function setPassword(Request $request, int $id, string $hash): RedirectResponse
    {
        $account = UserAccount::query()->findOrFail($id);
        abort_unless(hash_equals(sha1($account->email), $hash), 403);
        abort_unless($account->hasVerifiedEmail(), 403);

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ]);

        $account->forceFill([
            'password' => $validated['password'],
            'remember_token' => Str::random(60),
        ])->save();

        return redirect()->route('login')->with(
            'status',
            'Your email is verified and password is set. You can now sign in.'
        );
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);
        $email = Str::lower(trim($validated['email']));

        Password::broker()->sendResetLink(['email' => $email]);

        return back()->withInput(['email' => $email])->with(
            'status',
            'If an account exists for that email address, a password reset link will be sent shortly.'
        );
    }

    public function reset(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ]);

        $status = Password::broker()->reset(
            [
                'email' => Str::lower(trim($validated['email'])),
                'token' => $validated['token'],
                'password' => $validated['password'],
                'password_confirmation' => $request->input('password_confirmation'),
            ],
            function (UserAccount $account, string $password): void {
                $account->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($account));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => 'The reset link is invalid or has expired. Request a new one and try again.',
            ]);
        }

        return redirect()->route('login')->with(
            'status',
            'Your password has been reset. You can now sign in with your new password.'
        );
    }
}
