<?php

namespace App\Http\Requests\cartItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
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
            'cart_id' => 'nullable|string',
            'product_id' => 'nullable|string',
            'product_specication_id' => 'nullable|string',
            'quantity' => 'nullable|numeric',
        ];
    }
}
