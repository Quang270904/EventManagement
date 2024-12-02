<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',  // Mật khẩu yêu cầu có ít nhất 6 ký tự và khớp với "password_confirmation"
            'full_name' => 'required|string|max:255',  // Kiểm tra tên đầy đủ
            'phone' => 'nullable|string|max:15',  // Kiểm tra số điện thoại
            'address' => 'nullable|string|max:255',  // Kiểm tra địa chỉ
            'gender' => 'required|string',  // Kiểm tra giới tính
            'dob' => 'nullable|date',  // Kiểm tra ngày sinh
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Email is required.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'full_name.required' => 'Full name is required.',
            'gender.required' => 'Gender is required.',
        ];
    }
}
