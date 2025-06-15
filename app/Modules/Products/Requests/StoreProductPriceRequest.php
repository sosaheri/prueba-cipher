<?php

namespace App\Modules\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 * schema="StoreProductPriceRequest",
 * title="Store Product Price Request",
 * description="Datos para agregar un nuevo precio a un producto.",
 * required={"currency_id", "price"},
 * @OA\Property(
 * property="currency_id",
 * type="integer",
 * description="ID de la divisa para la cual se registra el precio. Debe existir y ser única para este producto.",
 * example=2
 * ),
 * @OA\Property(
 * property="price",
 * type="number",
 * format="float",
 * minimum=0,
 * description="El precio del producto en la divisa especificada.",
 * example=850.75
 * )
 * )
 */
class StoreProductPriceRequest extends FormRequest
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
            'currency_id' => [
                'required',
                'integer',
                Rule::exists('currencies', 'id'),
                Rule::unique('product_prices')->where(function ($query) use ($productId) {
                    return $query->where('product_id', $productId);
                }),
            ],
            'price' => ['required', 'numeric', 'min:0'],
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
            'currency_id.unique' => 'Este producto ya posee un precio definido para la moneda elegida.',
        ];
    }
}