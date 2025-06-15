<?php

namespace App\Modules\Currencies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * schema="Currency",
 * title="Currency",
 * description="Modelo de una divisa",
 * @OA\Property(
 * property="id",
 * type="integer",
 * format="int64",
 * description="Identificador único de la divisa"
 * ),
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Nombre de la divisa (ej. US Dollar)"
 * ),
 * @OA\Property(
 * property="symbol",
 * type="string",
 * description="Símbolo de la divisa (ej. USD)"
 * ),
 * @OA\Property(
 * property="exchange_rate",
 * type="number",
 * format="float",
 * description="Tasa de cambio de la divisa respecto a una base (ej. 1.0 para USD)"
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
 * )
 * )
 */
class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'symbol', 'exchange_rate'];
}