<?php

namespace App\Modules\Currencies\Services;

use App\Modules\Currencies\Models\Currency;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CurrencyService
{
    /**
     * Obtiene todas las divisas.
     */
    public function getAllCurrencies()
    {
        return Currency::all();
    }

    /**
     * Busca una divisa por su ID.
     *
     * @param int $id
     * @return Currency
     * @throws ModelNotFoundException
     */
    public function findCurrencyById(int $id): Currency
    {
        return Currency::findOrFail($id);
    }

    /**
     * Crea una nueva divisa.
     *
     * @param array $data
     * @return Currency
     */
    public function createCurrency(array $data): Currency
    {
        return Currency::create($data);
    }

    /**
     * Actualiza una divisa existente.
     *
     * @param int $id
     * @param array $data
     * @return Currency
     * @throws ModelNotFoundException
     */
    public function updateCurrency(int $id, array $data): Currency
    {
        $currency = $this->findCurrencyById($id);
        $currency->update($data);
        return $currency;
    }

    /**
     * Elimina una divisa.
     *
     * @param int $id
     * @return bool|null
     * @throws ModelNotFoundException
     */
    public function deleteCurrency(int $id): ?bool
    {
        $currency = $this->findCurrencyById($id);
        return $currency->delete();
    }
}