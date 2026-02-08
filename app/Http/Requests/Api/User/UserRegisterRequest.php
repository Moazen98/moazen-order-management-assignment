<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiParentRequest;
use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends ApiParentRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.The field is required'),
            'name.string'   => __('validation.The field must be a string'),
            'email.required' => __('validation.The field is required'),
            'email.email'    => __('validation.The field must be a valid email'),
            'email.unique'   => __('validation.The field must be unique'),
            'password.required' => __('validation.The field is required'),
            'password.min'      => __('validation.The field must be at least :min characters'),
            'password.same' =>__('validation.The Password is mismatch'),
            'confirm_password.min' =>__('validation.Password Field Must be greater than 6 character'),
            'confirm_password.required' =>__('validation.The field is required'),
        ];

    }
}
