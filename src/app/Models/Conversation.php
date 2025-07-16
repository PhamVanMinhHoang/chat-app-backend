<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => 'string',
            'name' => 'string',
        ];
    }

    /**
     * Get the users associated with the conversation.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_user', 'conversation_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get the messages associated with the conversation.
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }
}
