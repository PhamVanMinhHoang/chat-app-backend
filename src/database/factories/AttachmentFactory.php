<?php

namespace Database\Factories;

use App\Models\Attachment;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attachment>
 */
class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    public function definition()
    {
        $message = Message::where('has_attachment', true)->inRandomOrder()->first();

        return [
            'message_id' => $message->id,
            'file_url' => $this->faker->imageUrl(640, 480, 'cats'),
            'file_type' => 'image',
            'file_name' => $this->faker->word() . '.jpg',
            'file_size' => $this->faker->numberBetween(1000, 5000000),
        ];
    }
}
