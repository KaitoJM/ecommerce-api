<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute',
        'selection_type'
    ];

    public function scopeSearch(Builder $query, ?string $search) {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->where('attribute', 'like', "%{$search}%");
        });
    }
}
