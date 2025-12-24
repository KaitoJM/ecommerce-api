<?php

namespace App\Http\Requests\Admin\OrderItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderItemRequest extends FormRequest
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
            'order_id' => 'sometimes|exists:orders,id',
            'product_id' => 'sometimes|exists:products,id',
            'product_specification_id' => 'sometimes|exists:product_specifications,id',
            'product_snapshot_name' => 'sometimes|string',
            'product_snapshot_price' => 'sometimes|numeric',
            'quantity' => 'sometimes|numeric',
            'total' => 'numeric|sometimes',
        ];
    }
}
