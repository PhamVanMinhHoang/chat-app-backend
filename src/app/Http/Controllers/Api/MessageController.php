<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSent;

class MessageController extends Controller
{
    public function index(Conversation $conversation)
    {
        $this->authorize('view',$conversation);
        $msgs = $conversation->messages()
        ->with('sender:id,name','reactions','attachments')
        ->latest()
        ->paginate(50);
        return MessageResource::collection($msgs);
    }

    public function store(StoreMessageRequest $request, Conversation $conversation)
    {
        $this->authorize('view',$conversation);
        $data = $request->validated();

        $message = $conversation->messages()->create([
        'sender_id'      => $request->user()->id,
            'content'        => $data['content'] ?? null,
            'reply_to_id'    => $data['reply_to_id'] ?? null,
            'has_attachment' => false,
        ]);

        if (!empty($data['attachments'])) {
        foreach ($data['attachments'] as $file) {
            // handle upload logic
            $message->attachments()->create([/* ... */]);
                $message->update(['has_attachment'=>true]);
            }
        }

        // update last_message_id
        $conversation->update(['last_message_id'=>$message->id,'updated_at'=>now()]);

        broadcast(new MessageSent($message->load('sender','reactions','attachments'), $conversation->id))->toOthers();

        foreach ($conversation->users as $member) {
        if ($member->id !== $request->user()->id) {
            $member->notify(new \App\Notifications\NewMessageNotification($message));
            }
        }

        return new MessageResource($message);
    }
}
