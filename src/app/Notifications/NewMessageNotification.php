<?php

namespace App\Notifications;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use \Illuminate\Bus\Queueable;
    protected Message $message;
    public function __construct($message)
    {
        $this->message = $message;
    }
    public function via($notifiable): array
    {
        return ['database','broadcast'];
    }
    public function toDatabase($notifiable): array
    {
        return ['message'=>new MessageResource($this->message)];
    }
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage(
            [
                'message'=>new MessageResource($this->message)
            ]
        );
    }
}
