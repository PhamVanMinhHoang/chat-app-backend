<?php

namespace App\Http\Requests\Conversations;

use Illuminate\Foundation\Http\FormRequest;

class StoreConversationRequest extends FormRequest
{
    public function authorize(): true
    { return true; }

    public function rules(): array
    {
        return [
            'type'    => ['required','in:private,group'],
            'name'    => ['required_if:type,group','string','max:255'],
            'members' => ['required','array','min:1'],
            'members.*' => ['exists:users,id'],
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Loại cuộc trò chuyện là bắt buộc.',
            'type.in' => 'Loại cuộc trò chuyện phải là private hoặc group.',
            'name.required_if' => 'Tên cuộc trò chuyện là bắt buộc nếu loại là group.',
            'name.string' => 'Tên cuộc trò chuyện phải là một chuỗi.',
            'name.max' => 'Tên cuộc trò chuyện không được vượt quá 255 ký tự.',
            'members.required' => 'Danh sách thành viên là bắt buộc.',
            'members.array' => 'Danh sách thành viên phải là một mảng.',
            'members.min' => 'Danh sách thành viên phải có ít nhất một người.',
            'members.*.exists' => 'Một hoặc nhiều thành viên không tồn tại trong hệ thống.',
        ];
    }
}
