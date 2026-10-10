<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterSubscriptionConfirmation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class NewsletterVerificationController extends Controller
{
    public function verify(Request $request, int $id, string $hash): RedirectResponse
    {
        $subscription = DB::table('businessex_newsletter')->where('newsletter_id', $id)->first();
        abort_unless($subscription, 404);
        abort_unless(hash_equals(sha1($subscription->email), $hash), 403);

        if ($subscription->status !== 'S') {
            DB::table('businessex_newsletter')
                ->where('newsletter_id', $id)
                ->update(['status' => 'S', 'updated_at' => now()]);

            Mail::to($subscription->email)->queue(new NewsletterSubscriptionConfirmation($subscription->name ?? ''));
        }

        return redirect()->route('home')->withFragment('newsletter-subscription')->with(
            'newsletter_status',
            'Your email address is confirmed. You are now subscribed to the BusinessX newsletter.'
        );
    }
}
