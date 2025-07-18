<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Conversations\StoreConversationRequest;
use App\Http\Requests\Conversations\UpdateConversationRequest;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Services\ConversationService;
use Illuminate\Http\JsonResponse;
use Throwable;

class ConversationController
{
    protected ConversationService $conversationService;
    public function __construct(ConversationService $conversationService)
    {
        $this->conversationService = $conversationService;
    }

    public function index(): JsonResponse
    {
        try {
            $conversations = $this->conversationService->getAllConversations();
            return response()->json([
                'success' => true,
                'data' => $conversations,
            ], 200);
        } catch (Throwable $th) {
            // Handle exceptions, possibly log them or return a response
            return response()->json([
                'success' => false,
                'error' => 'Lỗi khi lấy danh sách cuộc trò chuyện',
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ],500);
        }
    }
    public function show(Conversation $conversation): JsonResponse
    {
        try {
            $conversation = new ConversationResource($conversation->load([
                'users:id,name',
                'lastMessage',
                'messages'=>fn($q)=> $q->latest()->take(50)
            ]));

            return response()->json([
                'success' => true,
                'data' => $conversation,
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => 'Lỗi khi lấy cuộc trò chuyện',
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Create a new conversation.
     */
    public function store(StoreConversationRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            if ($data['type']==='private') {
                if (count($data['members']) !== 1) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Cuộc trò chuyện riêng tư chỉ có thể có một thành viên khác ngoài bạn',
                    ], 400);
                }
                $existing = $this->conversationService->getPrivateConversation(
                    $request->user()->id,
                    $data['members'][0]
                );
                if ($existing) {
                    $conversation = new ConversationResource($existing->load('users:id,name'));

                    return response()->json([
                        'success' => true,
                        'data' => $conversation,
                    ], 201);
                }
            }
            $conv = Conversation::create(['type'=>$data['type'],'name'=>$data['name'] ?? null]);
            $conv->users()->attach($request->user()->id, ['role'=>'admin','joined_at'=>now()]);
            foreach ($data['members'] as $id) {
                $conv->users()->attach($id,['role'=>'member','joined_at'=>now()]);
            }
            $conversation = new ConversationResource($conv->load('users:id,name'));

            return response()->json([
                'success' => true,
                'data' => $conversation,
            ], 201);
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => 'Lỗi khi tạo cuộc trò chuyện',
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Update an existing conversation.
     */
    public function update(UpdateConversationRequest $request, Conversation $conversation): JsonResponse
    {
        try {
            $data = $request->validated();
            if ($conversation->type==='group' && isset($data['name'])) {
                $conversation->update(['name'=>$data['name']]);
            }
            if ($conversation->type==='group' && isset($data['members'])) {
                $sync=[];
            foreach ($data['members'] as $id) {
                    $sync[$id]=['role'=>'member','joined_at'=>now()];
            }
            $admin = $conversation->users()->wherePivot('role','admin')->first()->id;
            $sync[$admin]=['role'=>'admin','joined_at'=>now()];
            $conversation->users()->sync($sync);
        }
            $conversation = new ConversationResource($conversation->load('users:id,name'));

            return response()->json([
                'success' => true,
                'data' => $conversation,
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => 'Lỗi khi cập nhật cuộc trò chuyện',
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Delete a conversation.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $deleted = $this->conversationService->deleteConversation($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cuộc trò chuyện không tồn tại hoặc bạn không có quyền xóa',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cuộc trò chuyện đã được xóa thành công',
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => 'Lỗi khi xóa cuộc trò chuyện',
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ], 500);
        }
    }
}
