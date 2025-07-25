<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class MessageController extends Controller
{
    /**
     * Display a listing of messages in the specified conversation.
     * @param Conversation $conversation
     * @return JsonResponse
     */
    public function index(Conversation $conversation): JsonResponse
    {
        try {
            //        $this->authorize('view',$conversation);
            $msgs = $conversation->messages()
                ->with('sender:id,name,avatar','reactions','attachments')
                ->latest()
                ->paginate(50);

            return response()->json([
                'success' => true,
                'data' => MessageResource::collection($msgs),
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => 'Lỗi khi lấy tin nhắn',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    /**
     * Store a newly created message in the specified conversation.
     * @param StoreMessageRequest $request
     * @param Conversation $conversation
     * @return JsonResponse
     */
    public function store(StoreMessageRequest $request, Conversation $conversation): JsonResponse
    {
        try {
            DB::beginTransaction();
            //        $this->authorize('view',$conversation);
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

            // broadcast the message to the conversation channel
            broadcast(new MessageSent($message->load('sender','reactions','attachments'), $conversation->id))->toOthers();

            // notify other users in the conversation
            foreach ($conversation->users as $member) {
                if ($member->id !== $request->user()->id) {
                    $member->notify(new NewMessageNotification($message));
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => new MessageResource($message->load('sender','reactions','attachments')),
            ], 201);

        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => 'Lỗi khi gửi tin nhắn',
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ], 500);
        }
    }
}
