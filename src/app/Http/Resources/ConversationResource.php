<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends  JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'type'           => $this->type,
            'name'           => $this->name,
            'avatar'        => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'last_message'   => new MessageResource($this->whenLoaded('lastMessage')),
            'users'          => UserResource::collection($this->whenLoaded('users')),
            'updated_at'     => $this->updated_at->toDateTimeString(),
        ];
    }
}
