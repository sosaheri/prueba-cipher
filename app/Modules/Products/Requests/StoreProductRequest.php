<?php

namespace App\Modules\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 * schema="StoreProductRequest",
 * title="Store Product Request",
 * description="Datos para crear un nuevo producto",
 * required={"name", "price", "currency_id", "tax_cost", "manufacturing_cost"},
 * @OA\Property(
 * property="name",
 * type="string",
 * maxLength=255,
 * description="Nombre único del producto",
 * example="Smartphone X Pro"
 * ),
 * @OA\Property(
 * property="description",
 * type="string",
 * nullable=true,
 * description="Descripción detallada del producto",
 * example="Un smartphone de última generación con cámara de 108MP."
 * ),
 * @OA\Property(
 * property="price",
 * type="number",
 * format="float",
 * minimum=0,
 * description="Precio base del producto",
 * example=799.99
 * ),
 * @OA\Property(
 * property="currency_id",
 * type="integer",
 * description="ID de la divisa base del producto. Debe existir en la tabla 'currencies'.",
 * example=1
 * ),
 * @OA\Property(
 * property="tax_cost",
 * type="number",
 * format="float",
 * minimum=0,
 * description="Costo de impuestos aplicables al producto",
 * example=120.50
 * ),
 * @OA\Property(
 * property="manufacturing_cost",
 * type="number",
 * format="float",
 * minimum=0,
 * description="Costo de fabricación del producto",
 * example=450.00
 * )
 * )
 */
class StoreProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency_id' => [
                'required',
                'integer',
                Rule::exists('currencies', 'id'),
            ],
            'tax_cost' => ['required', 'numeric', 'min:0'],
            'manufacturing_cost' => ['required', 'numeric', 'min:0'],
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