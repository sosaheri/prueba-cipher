<?php

namespace App\Modules\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Currencies\Models\Currency;
use App\Modules\Products\Models\ProductPrice;

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