<?php

namespace App\Http\Requests\cartItem;

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
            'cart_id' => 'required',
            'product_id' => 'required',
            'product_specification_id' => 'required',
            'quantity' => 'required|numeric',
        ];
    }
}
