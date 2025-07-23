<?php

namespace App\Events;

use App\Http\Resources\MessageResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    public $message;
    protected $conversationId;
    public function __construct($message, $conversationId)
    {
        $this->message = $message;
        $this->conversationId = $conversationId;
    }
    public function broadcastOn(): PrivateChannel
    {
        Log::info('Broadcasting message sent event for conversation ID: ' . $this->conversationId);
        return new PrivateChannel('conversation.' . $this->conversationId);
    }
    public function broadcastWith(): array
    {
        return ['message'=>new MessageResource($this->message)];
    }
}
