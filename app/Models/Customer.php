<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'gender',
        'birthday',
        'phone',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch(Builder $query, ?string $search) {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('middle_name', 'like', "%{$search}%");
        });
    }

    public function scopeFilterGender(Builder $query, ?string $gender) {
        if (is_null($gender)) {
            return $query;
        }

        return $query->where(function($q) use ($gender) {
            $q->where('gender', $gender);
        });
    }

    public function scopeFilterBirthday(Builder $query, ?string $birthday) {
        if (is_null($birthday)) {
            return $query;
        }

        return $query->where(function($q) use ($birthday) {
            $q->where('birthday', $birthday);
        });
    }
}
