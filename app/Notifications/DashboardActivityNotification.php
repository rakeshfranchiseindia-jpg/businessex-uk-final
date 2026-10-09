<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DashboardActivityNotification extends Notification
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
        return ['database'];
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
}
