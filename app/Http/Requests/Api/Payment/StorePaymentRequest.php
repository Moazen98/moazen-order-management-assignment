<?php

namespace App\Http\Requests\Api\Payment;

use App\Http\Requests\Api\ApiParentRequest;

class StorePaymentRequest extends ApiParentRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'string', 'in:credit_card,paypal'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => __('validation.The field is required'),
            'payment_method.string' => __('validation.The field must be a string'),
            'payment_method.in' => __('validation.Invalid payment method'),
        ];
    }
}
