<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DashboardActivityNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $activityType,
        private readonly string $title,
        private readonly string $message,
        private readonly string $url,
        private readonly ?int $conversationId = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => $this->activityType,
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'conversation_id' => $this->conversationId,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $data = [
            'title' => $this->title,
            'name' => $notifiable->name ?? 'there',
            'bodyText' => $this->message,
            'actionUrl' => url($this->url),
        ];

        return (new MailMessage())
            ->subject($this->title)
            ->view(['html' => 'emails.dashboard.activity', 'text' => 'emails.dashboard.activity-text'], $data);
    }
}
