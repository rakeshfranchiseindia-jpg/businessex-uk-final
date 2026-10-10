<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterVerificationEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class SubscribeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'city' => ['required', 'string', 'max:100'],
        ]);

        $email = Str::lower(trim($validated['email']));
        $name = trim($validated['name']);
        $phone = trim($validated['phone']);
        $city = trim($validated['city']);

        [$newsletterId, $alreadyVerified] = DB::transaction(function () use ($email, $name, $phone, $city): array {
            $now = now();
            $userId = Auth::id();
            $subscription = DB::table('businessex_newsletter')
                ->whereRaw('LOWER(email) = ?', [$email])
                ->lockForUpdate()
                ->first();

            if ($subscription && $subscription->status === 'S') {
                return [(int) $subscription->newsletter_id, true];
            }

            if ($subscription) {
                $updates = [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'city' => $city,
                    'status' => 'P',
                    'unsubscribe_reason' => null,
                    'updated_at' => $now,
                ];

                if ($userId !== null) {
                    $updates['user_id'] = $userId;
                }

                DB::table('businessex_newsletter')
                    ->where('newsletter_id', $subscription->newsletter_id)
                    ->update($updates);

                return [(int) $subscription->newsletter_id, false];
            }

            $id = (int) DB::table('businessex_newsletter')->insertGetId([
                'user_id' => $userId ?? 0,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'city' => $city,
                'status' => 'P',
                'unsubscribe_reason' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            return [$id, false];
        });

        if ($alreadyVerified) {
            return redirect()->route('home')->withFragment('newsletter-subscription')->with(
                'newsletter_error',
                'This email address is already subscribed to the BusinessX newsletter.'
            );
        }

        try {
            Mail::to($email)->queue(new NewsletterVerificationEmail($newsletterId, $email, $name));
        } catch (Throwable $exception) {
            Log::error('BusinessX newsletter verification email could not be queued.', [
                'newsletter_id' => $newsletterId,
                'exception' => $exception,
            ]);

            return redirect()->route('home')->withFragment('newsletter-subscription')->with(
                'newsletter_error',
                'Your subscription was saved, but we could not queue the verification email. Please try again later.'
            );
        }

        return redirect()->route('home')->withFragment('newsletter-subscription')->with(
            'newsletter_status',
            'Please check your email and click the confirmation link to complete your subscription.'
        );
    }
}
