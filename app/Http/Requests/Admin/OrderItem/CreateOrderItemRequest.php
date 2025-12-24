<?php

namespace App\Http\Requests\Admin\OrderItem;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderItemRequest extends FormRequest
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
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'product_specification_id' => 'nullable|exists:product_specifications,id',
            'product_snapshot_name' => 'required|string',
            'product_snapshot_price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'total' => 'numeric|nullable',
        ];
    }
}
