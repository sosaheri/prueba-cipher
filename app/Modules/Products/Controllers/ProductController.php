<?php

namespace App\Modules\Products\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Products\Services\ProductService;
use App\Modules\Products\Requests\StoreProductRequest;
use App\Modules\Products\Requests\UpdateProductRequest;
use App\Modules\Products\Requests\StoreProductPriceRequest;
use App\Modules\Products\Resources\ProductResource;
use App\Modules\Products\Resources\ProductPriceResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     * path="/products",
     * tags={"Products"},
     * summary="Obtener lista de productos",
     * description="Retorna una lista paginada de productos, con opción de filtros y ordenación. Incluye la divisa base y todos los precios asociados a cada producto.",
     * @OA\Parameter(
     * name="name",
     * in="query",
     * description="Filtrar productos por nombre (búsqueda parcial)",
     * @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     * name="description",
     * in="query",
     * description="Filtrar productos por descripción (búsqueda parcial)",
     * @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     * name="currency_id",
     * in="query",
     * description="Filtrar productos por el ID de su divisa base",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     * name="order_by",
     * in="query",
     * description="Campo para ordenar la lista (ej. 'name', 'price')",
     * @OA\Schema(type="string", default="name")
     * ),
     * @OA\Parameter(
     * name="direction",
     * in="query",
     * description="Dirección de la ordenación ('asc' o 'desc')",
     * @OA\Schema(type="string", enum={"asc", "desc"}, default="asc")
     * ),
     * @OA\Parameter(
     * name="per_page",
     * in="query",
     * description="Número de elementos por página",
     * @OA\Schema(type="integer", default=15)
     * ),
     * @OA\Response(
     * response=200,
     * description="Lista de productos obtenida exitosamente.",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(
     * property="data",
     * type="array",
     * @OA\Items(ref="#/components/schemas/Product")
     * ),
     * @OA\Property(
     * property="links",
     * type="object"
     * ),
     * @OA\Property(
     * property="meta",
     * type="object"
     * )
     * )
     * )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['name', 'description', 'currency_id']);
        $orderBy = $request->input('order_by', 'name'); 
        $direction = $request->input('direction', 'asc'); 
        $perPage = $request->input('per_page', 15);

        $products = $this->productService->getAllProducts($filters, $orderBy, $direction, (int)$perPage);

        return ProductResource::collection($products)->response();
    }

    /**
     * @OA\Post(
     * path="/products",
     * tags={"Products"},
     * summary="Crear un nuevo producto",
     * description="Crea un nuevo producto y lo retorna. Los campos `price`, `tax_cost` y `manufacturing_cost` deben ser numéricos. `currency_id` debe existir en la tabla de divisas.",
     * @OA\RequestBody(
     * required=true,
     * description="Datos del producto a crear",
     * @OA\JsonContent(ref="#/components/schemas/StoreProductRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Producto creado exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/Product")
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
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated());
        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    /**
     * @OA\Get(
     * path="/products/{id}",
     * tags={"Products"},
     * summary="Obtener producto por ID",
     * description="Retorna un producto específico por su ID. Incluye la divisa base y todos los precios asociados.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del producto",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Producto obtenido exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/Product")
     * ),
     * @OA\Response(
     * response=404,
     * description="Producto no encontrado.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Resource not found.")
     * )
     * )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $product = $this->productService->findProductById($id);
            return (new ProductResource($product))->response();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    /**
     * @OA\Put(
     * path="/products/{id}",
     * tags={"Products"},
     * summary="Actualizar producto",
     * description="Actualiza un producto existente por su ID y lo retorna. Permite actualizar parcialmente el producto. Los campos `price`, `tax_cost` y `manufacturing_cost` deben ser numéricos. `currency_id` debe existir en la tabla de divisas.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del producto a actualizar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Datos del producto a actualizar",
     * @OA\JsonContent(ref="#/components/schemas/UpdateProductRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Producto actualizado exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/Product")
     * ),
     * @OA\Response(
     * response=404,
     * description="Producto no encontrado.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Resource not found.")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The given data was invalid."),
     * @OA\Property(property="errors", type="object", example={"name": {"The name has already been taken."}})
     * )
     * )
     * )
     */
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        try {
            $product = $this->productService->updateProduct($id, $request->validated());
            return (new ProductResource($product))->response();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    /**
     * @OA\Delete(
     * path="/products/{id}",
     * tags={"Products"},
     * summary="Eliminar producto",
     * description="Elimina un producto por su ID. También elimina todos los precios asociados a este producto.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del producto a eliminar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Producto eliminado exitosamente (No Content)."
     * ),
     * @OA\Response(
     * response=404,
     * description="Producto no encontrado.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Resource not found.")
     * )
     * )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->productService->deleteProduct($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    /**
     * @OA\Get(
     * path="/products/{product_id}/prices",
     * tags={"Product Prices"},
     * summary="Obtener precios de un producto",
     * description="Retorna una lista de todos los precios registrados para un producto específico en diferentes divisas.",
     * @OA\Parameter(
     * name="product_id",
     * in="path",
     * required=true,
     * description="ID del producto del cual obtener los precios",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Lista de precios obtenida exitosamente.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/ProductPrice")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Producto no encontrado.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Resource not found.")
     * )
     * )
     * )
     */
    public function prices(int $productId): JsonResponse
    {
        try {
            $prices = $this->productService->getProductPrices($productId);
            return ProductPriceResource::collection($prices)->response();
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    /**
     * @OA\Post(
     * path="/products/{product_id}/prices",
     * tags={"Product Prices"},
     * summary="Agregar un nuevo precio a un producto",
     * description="Asocia un nuevo precio a un producto existente para una divisa específica. La combinación de `product_id` y `currency_id` debe ser única.",
     * @OA\Parameter(
     * name="product_id",
     * in="path",
     * required=true,
     * description="ID del producto al cual agregar el precio",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Datos del nuevo precio",
     * @OA\JsonContent(ref="#/components/schemas/StoreProductPriceRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Precio agregado exitosamente.",
     * @OA\JsonContent(ref="#/components/schemas/ProductPrice")
     * ),
     * @OA\Response(
     * response=404,
     * description="Producto no encontrado.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Resource not found.")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación (ej. divisa ya existe para este producto o datos inválidos).",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The given data was invalid."),
     * @OA\Property(property="errors", type="object", example={"currency_id": {"This product already has a price defined for the selected currency."}})
     * )
     * )
     * )
     */
    public function storePrice(StoreProductPriceRequest $request, int $productId): JsonResponse
    {
        try {
            $price = $this->productService->addProductPrice($productId, $request->validated());
            return (new ProductPriceResource($price))->response()->setStatusCode(201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
}