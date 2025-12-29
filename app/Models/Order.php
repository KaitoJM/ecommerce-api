<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'session_id',
        'cart_id',
        'status_id',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function status() {
        return $this->belongsTo(OrderStatus::class);
    }

    public function history()
    {
        return $this->belongsToMany(OrderStatus::class, 'order_status_histories');
    }

    public function scopeSearch(Builder $query, ?string $search) {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->where('session_id', 'like', "%{$search}%");
        });
    }

    public function scopeFilterCustomerId(Builder $query, ?string $customerId) {
        if (is_null($customerId)) {
            return $query;
        }

        return $query->where(function($q) use ($customerId) {
            $q->where('customer_id', $customerId);
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

    public function scopeFilterStatusId(Builder $query, ?string $statusId) {
        if (is_null($statusId)) {
            return $query;
        }

        return $query->where(function($q) use ($statusId) {
            $q->where('status_id', $statusId);
        });
    }
}
