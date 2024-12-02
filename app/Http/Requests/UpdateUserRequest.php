<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules()
    {
        // Kiểm tra xem là tạo mới hay cập nhật người dùng
        $rules = [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->route('id'), // Kiểm tra trùng email, bỏ qua khi đang cập nhật
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'gender' => 'required',
            'dob' => 'required|date',
        ];

        return $rules;
    }

    /**
     * Xác định thông báo lỗi cho các quy tắc xác thực.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'full_name.required' => 'Full name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email is already taken.',
            'phone.max' => 'Phone number may not be greater than 15 characters.',
            'address.max' => 'Address may not be greater than 255 characters.',
            'dob.required' => 'Date is required',
            'gender.required' => 'Gender is required',
        ];
    }

    /**
     * Xử lý dữ liệu đầu vào trước khi trả về.
     *
     * @return array
     */
    public function prepareForValidation()
    {
        // Nếu bạn cần xử lý thêm trước khi xác thực (chẳng hạn, làm sạch dữ liệu)
        // Bạn có thể sử dụng phương thức này để chuẩn hóa dữ liệu
        $this->merge([
            'dob' => $this->dob ? \Carbon\Carbon::parse($this->dob)->format('Y-m-d') : null,  // Đảm bảo ngày tháng đúng định dạng
        ]);
    }
}
