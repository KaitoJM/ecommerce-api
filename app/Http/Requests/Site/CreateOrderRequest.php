<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            'cart_id' => 'string|required|exist:carts,id',
            'customer_id' => 'string|nullable|exist:customers,id',
            'email' => 'email|required',
            'discount_total'  => 'numeric|required',
            'tax_total'  => 'numeric|required',
        ];
    }
}
