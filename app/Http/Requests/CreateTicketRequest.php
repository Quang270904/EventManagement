<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
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
            'event_id' => 'required|exists:events,id', 
            'ticket_type' => 'required|string', 
            'price' => 'required|numeric', 
        ];
    }

    public function messages()
    {
        return [
            'event_id.required' => 'The event field is required.',
            'event_id.exists' => 'The selected event does not exist.',
            'ticket_type.required' => 'The ticket type is required.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
        ];
    }
}
