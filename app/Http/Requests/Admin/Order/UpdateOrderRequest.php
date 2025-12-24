<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'customer_id' => 'nullable|exists:customers,id',
            'session_id' => 'nullable',
            'cart_id' => 'nullable|exists:carts,id',
            'status_id' => 'nullable|exists:statuses,id',
            'subtotal' => 'numeric|nullable',
            'discount_total' => 'numeric|nullable',
            'tax_total' => 'numeric|nullable',
            'total' => 'numeric|nullable',
        ];
    }
}
