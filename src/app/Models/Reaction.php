<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'message_id',
        'user_id',
        'reaction_type',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByMessage($query, $messageId)
    {
        return $query->where('message_id', $messageId);
    }
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
