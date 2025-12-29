<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'color_code',
        'description'
    ];

    public function scopeSearch(Builder $query, ?string $search) {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->where('status', 'like', "%{$search}%");
        });
    }

    public function scopeFilterColorCode(Builder $query, ?string $colorCode) {
        if (is_null($colorCode)) {
            return $query;
        }

        return $query->where(function($q) use ($colorCode) {
            $q->where('color_code', $colorCode);
        });
    }
}
