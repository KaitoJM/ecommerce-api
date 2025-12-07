<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'summary',
        'description',
        'price',
        'published',
        'sale',
        'sale_price',
    ];
}
