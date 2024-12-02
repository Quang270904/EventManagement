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
            'email' => 'required|email|unique:users,email', // This ensures the email is unique
            'password' => 'required|min:6|confirmed',  // Mật khẩu yêu cầu có ít nhất 6 ký tự và khớp với "password_confirmation"
            'full_name' => 'required|string|max:255',  // Kiểm tra tên đầy đủ
            'phone' => 'required|string|max:15',  // Kiểm tra số điện thoại
            'address' => 'required|string|max:255',  // Kiểm tra địa chỉ
            'gender' => 'required|string',  // Kiểm tra giới tính
            'dob' => 'required|date',  // Kiểm tra ngày sinh
            'role_id' => 'required|exists:roles,id',  // Validate that the selected role exists in the 'roles' table

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
            'phone.required' => 'Phone is required.',
            'address.required' => 'Address is required.',
            'dob.required' => 'Dob is required.',
            'role_id.required' => 'Role is required.',
            'role_id.exists' => 'The selected role is invalid.',
        ];
    }
}
