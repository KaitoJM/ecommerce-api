<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'session_id',
        'status',
        'expires_at',
    ];

    public function scopeSearch(Builder $query, ?string $search) {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->where('session_id', 'like', "%{$search}%");
        });
    }

    public function scopeFilterStatus(Builder $query, ?string $status) {
        if (is_null($status)) {
            return $query;
        }

        return $query->where(function($q) use ($status) {
            $q->where('status', $status);
        });
    }

    public function scopeFilterExpiresAt(Builder $query, ?string $expiresAt) {
        if (is_null($expiresAt)) {
            return $query;
        }

        return $query->where(function($q) use ($expiresAt) {
            $q->where('expires_at', $expiresAt);
        });
    }
}
