<?php

namespace App\Http\Controllers\Api;

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
            $conversations = $this->conversationService->getAllConversations(auth()->id());
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
    public function show($id): JsonResponse
    {
        try {
            $conversation = $this->conversationService->getConversationById($id, auth()->id());
            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cuộc trò chuyện không tồn tại',
                ], 404);
            }
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
    public function store(): JsonResponse
    {
        try {
            $data = request()->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $data['user_id'] = auth()->id(); // Assuming the user is authenticated

            $conversation = $this->conversationService->createConversation($data);

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
    public function update($id): JsonResponse
    {
        try {
            $data = request()->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $conversation = $this->conversationService->updateConversation($id, $data);

            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cuộc trò chuyện không tồn tại hoặc bạn không có quyền sửa đổi',
                ], 404);
            }

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
