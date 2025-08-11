<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }
    public function rules(): array
    {
        $userId = Auth::id();

        // $emails = DB::table('users')
        //     ->where('is_active', 'active')
        //     ->where('is_delete', 0)
        //     ->pluck('email')
        //     ->toArray();

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'not_regex:/[@#$%]|^[\w\.\-]+@([\w\-]+\.)+[\w\-]{2,4}$/',
                Rule::unique('users', 'name')->ignore($userId),
            ],
            // 'email' => 'required|email|unique:users,email,' . $userId,
            'phone' => [
                'nullable',
                'regex:/^(0|\+84)[0-9]{9}$/'
            ],
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            // 'name.not_in' => 'Tên người dùng không thể là email.',
            'name.required' => 'Vui lòng nhập tên.',
            'name.min' => 'Tên quá ngắn (ít nhất 3 ký tự).',
            'name.max' => 'Tên quá dài (tối đa 50 ký tự).',
            'name.unique' => 'Tên người dùng đã được sử dụng.',
            'name.not_regex' => 'Tên không được chứa ký tự đặc biệt.',

            // 'email.required' => 'Vui lòng nhập email.',
            // 'email.email' => 'Email không hợp lệ.',
            // 'email.unique' => 'Email đã được sử dụng.',
            'phone.regex' => 'Số điện thoại không đúng định dạng Việt Nam.',


            'photo.image' => 'Ảnh đại diện phải là hình ảnh.',
            'photo.mimes' => 'Ảnh chỉ cho phép jpg, jpeg, png.',
            'photo.max' => 'Kích thước ảnh tối đa 2MB.',
        ];
    }
}
