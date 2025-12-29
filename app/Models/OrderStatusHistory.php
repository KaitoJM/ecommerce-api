<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $fillable = [
        'status_id',
        'order_id',
        'user_id',
    ];

    public function status() {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeFilterOrderId(Builder $query, ?string $orderId) {
        if (is_null($orderId)) {
            return $query;
        }

        return $query->where(function($q) use ($orderId) {
            $q->where('order_id', $orderId);
        });
    }
}
