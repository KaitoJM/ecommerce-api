<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_specification_id',
        'product_snapshot_name',
        'product_snapshot_price',
        'quantity',
        'total',
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function product_specification() {
        return $this->belongsTo(ProductSpecification::class);
    }

    public function scopeSearch(Builder $query, ?string $search) {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->where('product_snapshot_name', 'like', "%{$search}%")
            ->orWhere('product_snapshot_price', 'like', "%{$search}%");
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

    public function scopeFilterOrderId(Builder $query, ?string $orderId) {
        if (is_null($orderId)) {
            return $query;
        }

        return $query->where(function($q) use ($orderId) {
            $q->where('order_id', $orderId);
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
