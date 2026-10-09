<?php

namespace App\Mail;

use App\Models\UserAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class VerifyAccountEmail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public readonly string $verificationUrl;

    public function __construct(
        public readonly UserAccount $account
    ) {
        $this->verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $account->user_id,
                'hash' => sha1($account->email),
            ]
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Verify your BusinessX email address');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.verify-email',
            text: 'emails.auth.verify-email-text',
            with: [
                'name' => $this->account->name,
                'verificationUrl' => $this->verificationUrl,
            ],
        );
    }
}
