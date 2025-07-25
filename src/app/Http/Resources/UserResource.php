<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        $user = Auth::user();
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : asset('storage/' . 'avatars/default.png'),
            'is_current_user' => $user && $user->id === $this->id,
        ];
    }
}
