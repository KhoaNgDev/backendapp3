<?php

namespace App\Http\Requests\API\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class RepairRatingRequest extends FormRequest
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
            'repair_id' => 'required|exists:repairs,id',
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:200',
        ];
    }
    public function messages(): array
    {
        return [
            'repair_id.required' => 'Thiếu thông tin đợt sửa chữa.',
            'repair_id.exists' => 'Đợt sửa chữa không tồn tại.',

            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.integer' => 'Điểm đánh giá phải là số.',
            'rating.min' => 'Điểm đánh giá tối thiểu là 1 sao.',
            'rating.max' => 'Điểm đánh giá tối đa là 5 sao.',

            'feedback.string' => 'Nội dung đánh giá không hợp lệ.',
            'feedback.max' => 'Đánh giá không được vượt quá 200 ký tự.',
        ];
    }


}
