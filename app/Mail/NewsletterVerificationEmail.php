<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class NewsletterVerificationEmail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public readonly string $verifyUrl;

    public function __construct(
        public readonly int $newsletterId,
        public readonly string $email,
        public readonly string $name,
    ) {
        $this->verifyUrl = URL::temporarySignedRoute(
            'newsletter.verify',
            now()->addHours(24),
            [
                'id' => $this->newsletterId,
                'hash' => sha1($this->email),
            ]
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Confirm your BusinessX newsletter subscription');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.verify',
            text: 'emails.newsletter.verify-text',
            with: [
                'name' => $this->name,
                'verifyUrl' => $this->verifyUrl,
            ],
        );
    }
}
