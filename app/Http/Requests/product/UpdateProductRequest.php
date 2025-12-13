<?php

namespace App\Http\Requests\product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'summary' => 'sometimes',
            'description' => 'sometimes',
            'published' => 'sometimes|boolean',
            'categories' => 'sometimes|array',
            'categories.*' => 'sometimes|integer|exists:categories,id',
            'price' => 'sometimes|numeric',
            'stock' => 'sometimes|numeric',
        ];
    }
}
