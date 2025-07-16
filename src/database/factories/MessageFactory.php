<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        $conversation = Conversation::inRandomOrder()->first();
        $senderId = $conversation->users->random()->id;
        $hasAttachment = $this->faker->boolean(20);

        return [
            'conversation_id' => $conversation->id,
            'sender_id' => $senderId,
            'content' => $this->faker->sentence(),
            'is_system' => false,
            'is_edited' => false,
            'reply_to_id' => null,
            'has_attachment' => $hasAttachment,
        ];
    }
}
