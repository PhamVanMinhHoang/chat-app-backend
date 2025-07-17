<?php

namespace App\Http\Requests\Messages;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'content' => 'nullable|string|max:65535',
            'reply_to_id' => 'nullable|exists:messages,id',
            'attachments' => 'array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,wmv,flv|max:5120', // 5MB max
        ];
    }
}
