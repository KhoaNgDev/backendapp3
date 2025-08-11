<?php

namespace App\Http\Requests\API\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class AdminReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'repair_feedback_id' => 'required|exists:repair_feedback,id',
            'reply' => 'required|string|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'repair_feedback_id.required' => 'Thiếu thông tin phản hồi sửa chữa.',
            'repair_feedback_id.exists' => 'Phản hồi sửa chữa không tồn tại.',

            'reply.required' => 'Vui lòng nhập nội dung phản hồi.',
            'reply.string' => 'Nội dung phản hồi không hợp lệ.',
            'reply.max' => 'Nội dung phản hồi không được vượt quá 200 ký tự.',
        ];
    }


}
