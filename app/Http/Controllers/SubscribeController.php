<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\NewsletterSubscriptionConfirmation;
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
        $newsletterId = DB::transaction(function () use ($email, $name, $phone, $city): int {
            $now = now();
            $userId = Auth::id();
            $subscription = DB::table('businessex_newsletter')
                ->whereRaw('LOWER(email) = ?', [$email])
                ->lockForUpdate()
                ->first();

            if ($subscription) {
                $updates = [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'city' => $city,
                    'status' => 'S',
                    'unsubscribe_reason' => null,
                    'updated_at' => $now,
                ];

                if ($userId !== null) {
                    $updates['user_id'] = $userId;
                }

                DB::table('businessex_newsletter')
                    ->where('newsletter_id', $subscription->newsletter_id)
                    ->update($updates);

                return (int) $subscription->newsletter_id;
            }

            return (int) DB::table('businessex_newsletter')->insertGetId([
                'user_id' => $userId ?? 0,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'city' => $city,
                'status' => 'S',
                'unsubscribe_reason' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        try {
            Mail::to($email)->queue(new NewsletterSubscriptionConfirmation($name));
        } catch (Throwable $exception) {
            Log::error('BusinessX newsletter confirmation email could not be queued.', [
                'newsletter_id' => $newsletterId,
                'exception' => $exception,
            ]);

            return redirect()->route('home')->withFragment('newsletter-subscription')->with(
                'newsletter_error',
                'Your subscription was saved, but we could not queue the confirmation email. Please try again later.'
            );
        }

        return redirect()->route('home')->withFragment('newsletter-subscription')->with(
            'newsletter_status',
            'You are subscribed to the BusinessX newsletter. Please check your email for confirmation.'
        );
    }
}
