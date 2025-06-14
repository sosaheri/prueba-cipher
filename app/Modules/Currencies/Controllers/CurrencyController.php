<?php

namespace App\Modules\Currencies\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Currencies\Services\CurrencyService;
use App\Modules\Currencies\Requests\StoreCurrencyRequest;
use App\Modules\Currencies\Requests\UpdateCurrencyRequest;
use App\Modules\Currencies\Resources\CurrencyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CurrencyController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $currencies = $this->currencyService->getAllCurrencies();
        return CurrencyResource::collection($currencies)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCurrencyRequest $request): JsonResponse
    {
        $currency = $this->currencyService->createCurrency($request->validated());
        return (new CurrencyResource($currency))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $currency = $this->currencyService->findCurrencyById($id);
            return (new CurrencyResource($currency))->response();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Currency not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrencyRequest $request, int $id): JsonResponse
    {
        try {
            $currency = $this->currencyService->updateCurrency($id, $request->validated());
            return (new CurrencyResource($currency))->response();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Currency not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->currencyService->deleteCurrency($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Currency not found'], 404);
        }
    }
}