<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($this->user()->id),
            ],
            'city' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required'  => __('Name is required.'),
            'name.max'       => __('Name must not exceed 255 characters.'),
            'email.email'    => __('Please enter a valid email address.'),
            'email.unique'   => __('This email is already used by another account.'),
            'phone.max'      => __('Phone number must not exceed 20 characters.'),
            'phone.unique'   => __('This phone number is already used by another account.'),
            'city.max'       => __('City must not exceed 100 characters.'),
        ];
    }
}
