<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterSubscriptionConfirmation extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $name
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'You are subscribed to the BusinessX newsletter');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.subscription-confirmation',
            text: 'emails.newsletter.subscription-confirmation-text',
            with: ['name' => $this->name],
        );
    }
}
