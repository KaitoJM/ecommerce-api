<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'product_specification_id',
        'quantity',
    ];

    public function scopeSearch(Builder $query, ?string $search) {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->where('quantity', 'like', "%{$search}%");
        });
    }

    public function scopeFilterCartId(Builder $query, ?string $cartId) {
        if (is_null($cartId)) {
            return $query;
        }

        return $query->where(function($q) use ($cartId) {
            $q->where('cart_id', $cartId);
        });
    }

    public function scopeFilterProductId(Builder $query, ?string $productId) {
        if (is_null($productId)) {
            return $query;
        }

        return $query->where(function($q) use ($productId) {
            $q->where('product_id', $productId);
        });
    }

    public function scopeFilterProductSpecificationId(Builder $query, ?string $productSpecificationId) {
        if (is_null($productSpecificationId)) {
            return $query;
        }

        return $query->where(function($q) use ($productSpecificationId) {
            $q->where('product_specification_id', $productSpecificationId);
        });
    }
}
