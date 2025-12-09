<?php

namespace App\Http\Requests\productSpecification;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductSpecificationRequest extends FormRequest
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
            'product_id' => 'nullable',
            'combination' => 'nullable|string',
            'price' => 'nullable',
            'stock' => 'numeric|nullable',
            'default' => 'boolean|nullable',
            'sale' => 'boolean|nullable',
            'sale_price' => 'numeric|nullable',
        ];
    }
}
