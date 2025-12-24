<?php

namespace App\Models;

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
}
