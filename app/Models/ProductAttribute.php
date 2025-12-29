<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_id',
        'value',
        'color_value'
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function scopeFilterProductId(Builder $query, ?string $productId)
    {
        if (is_null($productId)) {
            return $query;
        }

        return $query->where(function($q) use ($productId) {
            $q->where('product_id', $productId);
        });
    }
}
