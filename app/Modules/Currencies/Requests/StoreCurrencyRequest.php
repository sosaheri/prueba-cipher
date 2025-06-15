<?php

namespace App\Modules\Currencies\Requests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 * schema="StoreCurrencyRequest",
 * title="Store Currency Request",
 * description="Datos para crear una nueva divisa",
 * required={"name", "symbol", "exchange_rate"},
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Nombre de la divisa (ej. US Dollar)",
 * example="US Dollar"
 * ),
 * @OA\Property(
 * property="symbol",
 * type="string",
 * description="Símbolo de la divisa (ej. USD)",
 * example="USD"
 * ),
 * @OA\Property(
 * property="exchange_rate",
 * type="number",
 * format="float",
 * description="Tasa de cambio de la divisa (ej. 1.0)",
 * example=1.0
 * )
 * )
 */
class StoreCurrencyRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:currencies,name'],
            'symbol' => ['required', 'string', 'max:10'],
            'exchange_rate' => ['required', 'numeric', 'min:0'],
        ];
    }
}