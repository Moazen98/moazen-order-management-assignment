<?php

namespace App\Http\Requests\Api\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderItemsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => __('validation.Items are required'),
            'items.array' => __('validation.Items must be an array'),
            'items.*.product_id.required' => __('validation.Product is required'),
            'items.*.quantity.required' => __('validation.Quantity is required'),
            'items.*.quantity.min' => __('validation.Quantity must be at least 1'),
        ];
    }
}
