<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'conversation_id',
        'content',
        'is_system',
        'is_edited',
        'reply_to_id',
        'has_attachment',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_edited' => 'boolean',
        'has_attachment' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    /**
     * Get the replies for the message.
    */
    public function replies()
    {
        return $this->hasMany(Message::class, 'reply_to_id');
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Get the Reactions for the message.
     * @return HasMany
     */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }


}
