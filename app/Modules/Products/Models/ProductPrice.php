<?php

namespace App\Modules\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Currencies\Models\Currency;

/**
 * @OA\Schema(
 * schema="ProductPrice",
 * title="ProductPrice",
 * description="Modelo del precio de un producto en una divisa específica",
 * @OA\Property(
 * property="id",
 * type="integer",
 * format="int64",
 * description="Identificador único del precio del producto"
 * ),
 * @OA\Property(
 * property="product_id",
 * type="integer",
 * description="Identificador del producto al que pertenece este precio"
 * ),
 * @OA\Property(
 * property="currency_id",
 * type="integer",
 * description="Identificador de la divisa para este precio"
 * ),
 * @OA\Property(
 * property="price",
 * type="number",
 * format="float",
 * description="Precio del producto en la divisa especificada"
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
 * description="Divisa asociada a este precio"
 * )
 * )
 */
class ProductPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'currency_id',
        'price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}