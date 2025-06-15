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
     * @OA\Get(
     * path="/currencies",
     * tags={"Currencies"},
     * summary="Obtener lista de divisas",
     * description="Retorna una lista de todas las divisas.",
     * security={{"sanctum":{}}}, 
     * @OA\Response(
     * response=200,
     * description="Lista de divisas obtenida exitosamente.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Currency")
     * )
     * )
     * )
     */
    public function index(): JsonResponse
    {
        $currencies = $this->currencyService->getAllCurrencies();
        return CurrencyResource::collection($currencies)->response();
    }

    /**
     * @OA\Post(
     * path="/currencies",
     * tags={"Currencies"},
     * summary="Crear una nueva divisa",
     * description="Crea una nueva divisa y la retorna.",
     * security={{"sanctum":{}}}, 
     * @OA\RequestBody(
     * required=true,
     * description="Datos de la divisa a crear",
     * @OA\JsonContent(ref="#/components/schemas/StoreCurrencyRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Divisa creada exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/Currency")
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The given data was invalid."),
     * @OA\Property(property="errors", type="object", example={"name": {"The name field is required."}})
     * )
     * )
     * )
     */
    public function store(StoreCurrencyRequest $request): JsonResponse
    {
        $currency = $this->currencyService->createCurrency($request->validated());
        return (new CurrencyResource($currency))->response()->setStatusCode(201);
    }

    /**
     * @OA\Get(
     * path="/currencies/{id}",
     * tags={"Currencies"},
     * summary="Obtener divisa por ID",
     * description="Retorna una divisa específica por su ID.",
     * security={{"sanctum":{}}}, 
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la divisa",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Divisa obtenida exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/Currency")
     * ),
     * @OA\Response(
     * response=404,
     * description="Divisa no encontrada.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Resource not found.")
     * )
     * )
     * )
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
     * @OA\Put(
     * path="/currencies/{id}",
     * tags={"Currencies"},
     * summary="Actualizar divisa",
     * description="Actualiza una divisa existente por su ID y la retorna.",
     * security={{"sanctum":{}}}, 
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la divisa a actualizar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Datos de la divisa a actualizar",
     * @OA\JsonContent(ref="#/components/schemas/UpdateCurrencyRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Divisa actualizada exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/Currency")
     * ),
     * @OA\Response(
     * response=404,
     * description="Divisa no encontrada.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Resource not found.")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación."
     * )
     * )
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
     * @OA\Delete(
     * path="/currencies/{id}",
     * tags={"Currencies"},
     * summary="Eliminar divisa",
     * description="Elimina una divisa por su ID.",
     * security={{"sanctum":{}}}, 
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la divisa a eliminar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Divisa eliminada exitosamente (No Content)."
     * ),
     * @OA\Response(
     * response=404,
     * description="Divisa no encontrada.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Resource not found.")
     * )
     * )
     * )
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