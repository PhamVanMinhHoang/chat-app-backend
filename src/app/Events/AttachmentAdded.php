<?php

namespace App\Events;

use App\Models\Attachment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class AttachmentAdded implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    public $attachment;
    protected $conversationId;

    public function __construct(Attachment $attachment, $conversationId)
    {
        $this->attachment = $attachment;
        $this->conversationId = $conversationId;
    }

    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel('conversation.' . $this->conversationId);
    }

    public function broadcastWith(): array
    {
        return ['attachment' => $this->attachment];
    }
}
