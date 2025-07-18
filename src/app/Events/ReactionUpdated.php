<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Reaction;

class ReactionUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public Reaction $reaction;
    protected $conversationId;

    public function  __construct(Reaction $reaction, $conversationId)
    {
        $this->reaction = $reaction;
        $this->conversationId = $conversationId;
    }

    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel('conversation.' . $this->conversationId);
    }

    public function broadcastWith(): array
    {
        return [
            'reaction' => $this->reaction,
        ];
    }
}
