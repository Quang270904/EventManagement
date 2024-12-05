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
        $userId = $this->route('id');

        return [
            'email' => 'required|email|unique:users,email,' . $userId,  
            'password' => 'required|min:6|confirmed',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'gender' => 'required|string',
            'dob' => 'required|date',
            'role_id' => 'required|exists:roles,id',

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
