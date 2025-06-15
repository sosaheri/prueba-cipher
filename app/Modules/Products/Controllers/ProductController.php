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
     * Display a listing of the resource.
     * GET /api/products
     * Permite filtros por 'name', 'description', 'currency_id'
     * Permite paginación con 'per_page'
     * Permite ordenación con 'order_by' y 'direction'
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
     * Store a newly created resource in storage.
     * POST /api/products
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct($request->validated());
        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     * GET /api/products/{id}
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
     * Update the specified resource in storage.
     * PUT /api/products/{id}
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
     * Remove the specified resource from storage.
     * DELETE /api/products/{id}
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
     * Display a listing of prices for a specific product.
     * GET /api/products/{id}/prices
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
     * Store a new price for a specified product.
     * POST /api/products/{id}/prices
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