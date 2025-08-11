<?php

namespace App\Http\Requests\API\Frontend;

use Illuminate\Foundation\Http\FormRequest;
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'regex:/^0\d{9}$/'],
            'remember' => ['boolean'],
            'password' => ['required_without:otp'],
            'otp' => ['required_without:password'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'password.required_without' => 'Vui lòng nhập mật khẩu.',
            'otp.required_without' => 'Vui lòng nhập OTP.',
        ];
    }
}
