<?php

namespace App\Http\Requests\Api\Order;

use App\Http\Requests\Api\ApiParentRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends ApiParentRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => __('validation.The field is required'),
            'items.array' => __('validation.The field must be an array'),
            'items.min' => __('validation.At least one item is required'),

            'items.*.product_id.required' => __('validation.The field is required'),
            'items.*.product_id.exists' => __('validation.Invalid product'),

            'items.*.quantity.required' => __('validation.The field is required'),
            'items.*.quantity.integer' => __('validation.The field must be an integer'),
            'items.*.quantity.min' => __('validation.The field must be at least :min'),
        ];
    }
}
