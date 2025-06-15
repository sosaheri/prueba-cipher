<?php

namespace App\Modules\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 * schema="UpdateProductRequest",
 * title="Update Product Request",
 * description="Datos para actualizar un producto existente (parcialmente).",
 * @OA\Property(
 * property="name",
 * type="string",
 * maxLength=255,
 * description="Nombre único del producto",
 * example="Smartphone X Pro (Updated)"
 * ),
 * @OA\Property(
 * property="description",
 * type="string",
 * nullable=true,
 * description="Descripción detallada del producto",
 * example="Una descripción actualizada del smartphone X Pro."
 * ),
 * @OA\Property(
 * property="price",
 * type="number",
 * format="float",
 * minimum=0,
 * description="Precio base del producto",
 * example=829.99
 * ),
 * @OA\Property(
 * property="currency_id",
 * type="integer",
 * description="ID de la divisa base del producto. Debe existir en la tabla 'currencies'.",
 * example=2
 * ),
 * @OA\Property(
 * property="tax_cost",
 * type="number",
 * format="float",
 * minimum=0,
 * description="Costo de impuestos aplicables al producto",
 * example=125.00
 * ),
 * @OA\Property(
 * property="manufacturing_cost",
 * type="number",
 * format="float",
 * minimum=0,
 * description="Costo de fabricación del producto",
 * example=460.00
 * )
 * )
 */
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