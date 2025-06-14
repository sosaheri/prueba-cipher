<?php

namespace App\Modules\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $productId = $this->route('product');

        return [
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('products', 'name')->ignore($productId)],
            'description' => ['sometimes', 'nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'currency_id' => [
                'sometimes',
                'integer',
                Rule::exists('currencies', 'id'),
            ],
            'tax_cost' => ['sometimes', 'numeric', 'min:0'],
            'manufacturing_cost' => ['sometimes', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'currency_id.exists' => 'La Moneda elegida no es valida o no existe.',
            'name.unique' => 'Un producto con este nombre ya existe.',
        ];
    }
}