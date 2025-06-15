<?php

namespace App\Modules\Products\Services;

use App\Modules\Products\Models\Product;
use App\Modules\Products\Models\ProductPrice;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    /**
     * Obtiene una lista de productos con paginación, filtrado y ordenación.
     *
     * @param array $filters (e.g., ['name' => 'Laptop', 'currency_id' => 1])
     * @param string $orderBy
     * @param string $direction
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllProducts(
        array $filters = [],
        string $orderBy = 'name',
        string $direction = 'asc',
        int $perPage = 15
    ): LengthAwarePaginator
    {

        
        $query = Product::query()->with(['currency', 'prices.currency']);

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if (isset($filters['description'])) {
            $query->where('description', 'like', '%' . $filters['description'] . '%');
        }
        if (isset($filters['currency_id'])) {
            $query->where('currency_id', $filters['currency_id']);
        }

        $query->orderBy($orderBy, $direction);

        return $query->paginate($perPage);
    }

    /**
     * Busca un producto por su ID.
     *
     * @param int $id
     * @return Product
     * @throws ModelNotFoundException
     */
    public function findProductById(int $id): Product
    {
        return Product::with(['currency', 'prices.currency'])->findOrFail($id);
    }

    /**
     * Crea un nuevo producto.
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Actualiza un producto existente.
     *
     * @param int $id
     * @param array $data
     * @return Product
     * @throws ModelNotFoundException
     */
    public function updateProduct(int $id, array $data): Product
    {
        $product = $this->findProductById($id);
        $product->update($data);
        return $product;
    }

    /**
     * Elimina un producto.
     *
     * @param int $id
     * @return bool|null
     * @throws ModelNotFoundException
     */
    public function deleteProduct(int $id): ?bool
    {
        $product = $this->findProductById($id);
        return $product->delete();
    }

    /**
     * Obtiene los precios de un producto.
     *
     * @param int $productId
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws ModelNotFoundException Si el producto no existe
     */
    public function getProductPrices(int $productId)
    {
        $product = $this->findProductById($productId);

        return $product->prices;
    }

    /**
     * Agrega un nuevo precio a un producto.
     *
     * @param int $productId
     * @param array $data ['currency_id', 'price']
     * @return ProductPrice
     * @throws ModelNotFoundException Si el producto no existe
     * @throws \Illuminate\Database\QueryException Si hay una restricción de unicidad (ya manejada por StoreProductPriceRequest)
     */
    public function addProductPrice(int $productId, array $data): ProductPrice
    {
        $product = $this->findProductById($productId); 
       return $product->prices()->create($data);
    }
}