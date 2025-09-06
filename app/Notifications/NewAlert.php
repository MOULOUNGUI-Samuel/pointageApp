<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $body,
        public ?string $url = null,
        public array $extra = []
    ) {
        $this->afterCommit = true;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast']; // ✅ plus de WebPushChannel
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => $this->title,
            'body'  => $this->body,
            'url'   => $this->url ?? url('/notifications'),
            'extra' => $this->extra,
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body'  => $this->body,
            'url'   => $this->url ?? url('/notifications'),
            'extra' => $this->extra,
        ];
    }
}
