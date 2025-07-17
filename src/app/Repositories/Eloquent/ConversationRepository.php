<?php

namespace App\Repositories\Eloquent;

use App\Models\Conversation;
use App\Repositories\Interfaces\ConversationRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

class ConversationRepository implements  ConversationRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = app()->make('App\Models\Conversation');
    }

    /**
     * Lấy tất cả các cuộc trò chuyện của người dùng. Cùng với tin nhắn cuối cùng
     * @param $user
     * @throws Throwable
     */

    public function getAllConversations($user)
    {
        try {
            return $user->conversations()
                ->select('conversations.id','conversations.name','conversations.type','conversations.last_message_id','conversations.updated_at')
                ->with([
                    'users:id,name',
                    'lastMessage:id,conversation_id,sender_id,content,created_at'
                ])
                ->orderByDesc('conversations.updated_at')
                ->paginate(20);
        } catch (Throwable $e) {
            throw new Exception('Error fetching conversations: ' . $e->getMessage());
        }
    }

    public function getConversationById(int $conversationId)
    {
        return $this->model->where('id', $conversationId)->first();
    }

    public function createConversation(array $data)
    {
        return $this->model->create($data);
    }

    public function updateConversation(int $conversationId, array $data)
    {
        $conversation = $this->getConversationById($conversationId);
        if ($conversation) {
            return $conversation->update($data);
        }
        return false;
    }

    public function deleteConversation(int $conversationId)
    {
        $conversation = $this->getConversationById($conversationId);
        if ($conversation) {
            return $conversation->delete();
        }
        return false;
    }
}
