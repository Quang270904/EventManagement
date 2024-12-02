<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventOfManagerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048', 
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
            'name.required' => 'Event name is required.',
            'description.required' => 'Event description is required.',
            'location.required' => 'Event location is required.',
            'start_time.required' => 'Start time is required.',
            'end_time.required' => 'End time is required.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'image.max' => 'The image size must not exceed 2MB.',
        ];
    }
}
