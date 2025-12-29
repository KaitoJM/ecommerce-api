<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'source',
        'cover',
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

    public function scopeFilterCover(Builder $query, ?bool $cover)
    {
        if (is_null($cover)) {
            return $query;
        }

        return $query->where(function($q) use ($cover) {
            $q->where('cover', $cover);
        });
    }
}
