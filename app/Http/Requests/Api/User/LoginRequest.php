<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiParentRequest;

class LoginRequest extends ApiParentRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('validation.The field is required'),
            'email.email'    => __('validation.The field must be a valid email'),
            'password.required' => __('validation.The field is required'),
            'password.min'      => __('validation.The field must be at least :min characters'),
        ];

    }
}
