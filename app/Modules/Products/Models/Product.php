<?php

namespace App\Modules\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Currencies\Models\Currency;
use App\Modules\Products\Models\ProductPrice;

/**
 * @OA\Schema(
 * schema="Product",
 * title="Product",
 * description="Modelo de un producto",
 * @OA\Property(
 * property="id",
 * type="integer",
 * format="int64",
 * description="Identificador único del producto"
 * ),
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Nombre del producto"
 * ),
 * @OA\Property(
 * property="description",
 * type="string",
 * nullable=true,
 * description="Descripción del producto"
 * ),
 * @OA\Property(
 * property="price",
 * type="number",
 * format="float",
 * description="Precio del producto en la divisa base"
 * ),
 * @OA\Property(
 * property="currency_id",
 * type="integer",
 * description="Identificador de la divisa base"
 * ),
 * @OA\Property(
 * property="tax_cost",
 * type="number",
 * format="float",
 * description="Costo de impuestos del producto"
 * ),
 * @OA\Property(
 * property="manufacturing_cost",
 * type="number",
 * format="float",
 * description="Costo de fabricación del producto"
 * ),
 * @OA\Property(
 * property="created_at",
 * type="string",
 * format="date-time",
 * description="Fecha y hora de creación"
 * ),
 * @OA\Property(
 * property="updated_at",
 * type="string",
 * format="date-time",
 * description="Fecha y hora de última actualización"
 * ),
 * @OA\Property(
 * property="currency",
 * ref="#/components/schemas/Currency",
 * description="Divisa base del producto"
 * ),
 * @OA\Property(
 * property="prices",
 * type="array",
 * @OA\Items(ref="#/components/schemas/ProductPrice"),
 * description="Lista de precios del producto en diferentes divisas"
 * )
 * )
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency_id',
        'tax_cost',
        'manufacturing_cost'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }
}