<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class ReactionRemoved implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    public $messageId;
    public $userId;
    protected $conversationId;

    public function __construct($messageId, $userId, $conversationId)
    {
        $this->messageId = $messageId;
        $this->userId = $userId;
        $this->conversationId = $conversationId;
    }

    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel('conversation.' . $this->conversationId);
    }

    public function broadcastWith(): array
    {
        return [
            'message_id' => $this->messageId,
            'user_id' => $this->userId
        ];
    }
}
