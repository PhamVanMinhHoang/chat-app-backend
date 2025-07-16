<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $conv = Conversation::first();

        Message::create([
            'conversation_id' => $conv->id,
            'sender_id'       => 5,
            'content'         => 'Chào bạn!',
            'is_system'       => false,
            'is_edited'       => false,
            'has_attachment'  => false,
        ]);
    }
}
