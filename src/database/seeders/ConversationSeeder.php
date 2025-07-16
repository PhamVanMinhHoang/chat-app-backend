<?php

namespace Database\Seeders;

use App\Models\Conversation;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo conversation 1-1 thử nghiệm giữa user 5 và 6
        $conv = Conversation::create([ 'type' => 'private', 'name' => null ]);
        $conv->users()->attach([5 => ['role'=>'member'], 6 => ['role'=>'member']]);

        // Tạo conversation nhóm thử nghiệm
        $group = Conversation::create([ 'type' => 'group', 'name' => 'Nhóm Chat' ]);
        $group->users()->attach([
            7 => ['role'=>'admin'],
            8 => ['role'=>'member'],
            9 => ['role'=>'member'],
        ]);
    }
}
