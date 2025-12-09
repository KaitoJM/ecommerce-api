<?php

namespace App\Http\Services;

use App\Models\Product;
use phpDocumentor\Reflection\Types\Boolean;

class ProductService {
    /**
     * Get products for the given user with optional filters.
     *
     * @param string|null $search Optional search term to filter by name or description
     * @param array|null $filters Optional filters to apply to the query
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product>
     */
    public function getProducts(?string $search = null, $filters = null) {
        $query = Product::with(['categories', 'images']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($filters['published'] ?? false) {
            $query->where('published', $filters['published']);
        }

        return $query->get();
    }

    /**
     *
     * @param  array{
     *     name: string
     *     description?: string|null
     * }  $params
     * @return \App\Models\Product
     */
    public function createProduct($params) {
        $createdProduct = Product::create([
            'name' => $params['name'],
            'description' => $params['description'] ?? '',
        ]);

        return $createdProduct;
    }

    /**
     * Get a product by its ID.
     *
     * @param int $id The ID of the product to get
     * @return \App\Models\Product
     */
    public function getProductById(int $id) {
        return Product::with(['categories', 'images'])->findOrFail($id);
    }
    
    /**
     * Update a product by its ID.
     *
     * @param int $id The ID of the product to update
     * @param array $params The parameters to update the product with
     * @return \App\Models\Product
     */ 
    public function updateProduct(int $id, array $params) {
        $product = $this->getProductById($id);

        $product->update($params);

        return $product;
    }
    
    /**
     * Delete a product by its ID.
     *
     * @param int $id The ID of the product to delete
     * @return \App\Models\Product
     */
    public function deleteProduct(int $id) {
        $product = $this->getProductById($id);

        $product->delete();

        return $product;
    }

    public function attachCategories(Product $product, $category_ids = []) {
        $product->categories()->sync($category_ids);
    }
}