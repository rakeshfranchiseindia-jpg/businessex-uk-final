<?php

namespace App\Mail;

use App\Models\UserAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetAccountPasswordEmail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly UserAccount $account,
        public readonly string $token
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Reset your BusinessX password');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.reset-password',
            text: 'emails.auth.reset-password-text',
            with: [
                'name' => $this->account->name,
                'resetUrl' => route('reset-password', [
                    'token' => $this->token,
                    'email' => $this->account->email,
                ]),
            ],
        );
    }
}
