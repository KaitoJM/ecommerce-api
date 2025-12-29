<?php

namespace App\Http\Requests\Admin\OrderStatus;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderStatusRequest extends FormRequest
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
            'status' => 'required|string',
            'color_code' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }
}
