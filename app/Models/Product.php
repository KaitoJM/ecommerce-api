<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'summary',
        'description',
        'published',
        'brand_id'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images() {
        return $this->hasMany(ProductImage::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes');
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }

    public function scopeSearch(Builder $query, ?string $search)
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopePublished(Builder $query, ?bool $published)
    {
        if (is_null($published)) {
            return $query;
        }

        return $query->where('published', $published);
    }

    public function scopeFilterCategories(Builder $query, array $categoryIds)
    {
        if (empty($categoryIds)) {
            return $query;
        }

        foreach ($categoryIds as $categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        return $query;
    }

    public function scopeFilterPrice(Builder $query, ?float $min, ?float $max)
    {
        if (is_null($min) && is_null($max)) {
            return $query;
        }

        return $query->whereHas('specifications', function ($q) use ($min, $max) {
            if (!is_null($min)) {
                $q->where('price', '>=', $min);
            }

            if (!is_null($max)) {
                $q->where('price', '<=', $max);
            }
        });
    }
}
