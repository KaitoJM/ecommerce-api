<?php

namespace App\Models;

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
}
