<?php

namespace App\Http\Requests\API\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class RepairLookupRequest extends FormRequest
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
            'phone' => [
                'nullable',
                'required_without:plate',
                'digits:10',
                'regex:/^0[0-9]{9}$/',
                'min:10',
                'max:10',
            ],
            'plate' => [
                'nullable',
                'required_without:phone',
                'regex:/^[0-9A-Z]{5,10}$/',
                'min:5',
                'max:10',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required_without' => 'Vui lòng nhập số điện thoại hoặc biển số xe.',
            'phone.digits' => 'Số điện thoại phải đúng 10 chữ số.',
            'phone.min' => 'Số điện thoại phải có đúng 10 chữ số.',
            'phone.max' => 'Số điện thoại phải có đúng 10 chữ số.',
            'phone.regex' => 'Số điện thoại phải bắt đầu bằng số 0 và chỉ chứa chữ số.',

            'plate.required_without' => 'Vui lòng nhập biển số xe hoặc số điện thoại.',
            'plate.regex' => 'Biển số xe phải từ 5–10 ký tự, chỉ gồm chữ in hoa và số, không chứa ký tự đặc biệt.',
            'plate.min' => 'Biển số xe phải có ít nhất 5 ký tự.',
            'plate.max' => 'Biển số xe không được quá 10 ký tự.',
        ];
    }


}
