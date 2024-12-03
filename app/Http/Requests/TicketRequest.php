<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
            'ticket_type' => 'required|in:regular,vip,discounted',  
            'price' => 'required|numeric|min:0',  
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'ticket_type.required' => 'Ticket type is required.',
            'ticket_type.in' => 'Ticket type must be one of the following: regular, vip, discounted.',
            'price.required' => 'Price is required.',
        ];
    }
}
