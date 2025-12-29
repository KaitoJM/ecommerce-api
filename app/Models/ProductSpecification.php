<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'combination',
        'price',
        'stock',
        'default',
        'sale',
        'sale_price',
        'images',
    ];

    public function scopeFilterProductId(Builder $query, ?string $productId)
    {
        if (is_null($productId)) {
            return $query;
        }

        return $query->where(function($q) use ($productId) {
            $q->where('product_id', $productId);
        });
    }

    public function scopeFilterDefault(Builder $query, ?bool $default)
    {
        if (is_null($default)) {
            return $query;
        }

        return $query->where(function($q) use ($default) {
            $q->where('default', $default);
        });
    }

    public function scopeFilterSale(Builder $query, ?bool $sale)
    {
        if (is_null($sale)) {
            return $query;
        }

        return $query->where(function($q) use ($sale) {
            $q->where('sale', $sale);
        });
    }
}
