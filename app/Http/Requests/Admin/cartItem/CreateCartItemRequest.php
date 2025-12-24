<?php

namespace App\Http\Requests\Admin\cartItem;

use Illuminate\Foundation\Http\FormRequest;

class CreateCartItemRequest extends FormRequest
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
            'cart_id' => 'required|exists:carts,id',
            'product_id' => 'required|exists:products,id',
            'product_specification_id' => 'required|exists:product_specifications,id',
            'quantity' => 'required|numeric',
        ];
    }
}
