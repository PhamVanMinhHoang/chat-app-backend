<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Attachment;
use App\Models\Reaction;
class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = FakerFactory::create();

        // Tạo 50 user
        $users = User::factory(50)->create();

        // Tạo 30 conversation (gồm private và group)
        $convs = Conversation::factory(30)->create();

        // Đính kèm thành viên cho mỗi conversation
        foreach ($convs as $conv) {
            if ($conv->type === 'private') {
                $members = $users->random(2)->pluck('id');
            } else {
                $members = $users->random(rand(3, 10))->pluck('id');
            }

            $pivot = [];
            foreach ($members as $id) {
                $pivot[$id] = [
                    'role'      => $conv->type === 'group'
                        ? ($id === $members->first() ? 'admin' : 'member')
                        : 'member',
                    'joined_at' => now(),
                ];
            }
            $conv->users()->attach($pivot);
        }

        // Tạo 200 messages
        $messages = Message::factory(200)->create();

        // Tạo attachments (tối đa 100)
        $attachmentsCount = 0;
        foreach ($messages as $msg) {
            if ($msg->has_attachment && $attachmentsCount < 100) {
                Attachment::factory()
                    ->state(['message_id' => $msg->id])
                    ->create();
                $attachmentsCount++;
            }
        }

        // Tạo reactions đảm bảo không trùng
        $reactionCount   = 0;
        $totalReactions  = 300;
        $messageIds      = $messages->pluck('id')->toArray();
        $userIds         = $users->pluck('id')->toArray();

        while ($reactionCount < $totalReactions) {
            $msgId  = $faker->randomElement($messageIds);
            $userId = $faker->randomElement($userIds);
            $type   = $faker->randomElement(['like', 'love', 'haha', 'wow', 'sad', 'angry']);

            $exists = Reaction::where('message_id', $msgId)
                ->where('user_id', $userId)
                ->exists();

            if (! $exists) {
                Reaction::create([
                    'message_id'   => $msgId,
                    'user_id'      => $userId,
                    'reaction_type'=> $type,
                ]);
                $reactionCount++;
            }
        }
    }
}
