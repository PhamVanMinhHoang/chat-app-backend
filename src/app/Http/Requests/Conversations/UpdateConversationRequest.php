<?php

namespace App\Http\Requests\Conversations;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConversationRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'name'    => ['sometimes','required_if:conversation.type,group','string','max:255'],
            'members' => ['sometimes','array','min:1'],
            'members.*' => ['exists:users,id'],
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi
     */
    public function messages()
    {
        return [
            'name.required_if' => 'Tên cuộc trò chuyện là bắt buộc nếu loại là group.',
            'name.string' => 'Tên cuộc trò chuyện phải là một chuỗi.',
            'name.max' => 'Tên cuộc trò chuyện không được vượt quá 255 ký tự.',
            'members.array' => 'Danh sách thành viên phải là một mảng.',
            'members.min' => 'Danh sách thành viên phải có ít nhất một người.',
            'members.*.exists' => 'Một hoặc nhiều thành viên không tồn tại trong hệ thống.',
        ];
    }
}
