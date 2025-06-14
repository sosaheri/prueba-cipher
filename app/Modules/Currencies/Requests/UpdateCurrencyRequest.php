<?php

namespace App\Modules\Currencies\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCurrencyRequest extends FormRequest
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

        $currencyId = $this->route('currency');

        return [
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('currencies', 'name')->ignore($currencyId)],
            'symbol' => ['sometimes', 'string', 'max:10'],
            'exchange_rate' => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}