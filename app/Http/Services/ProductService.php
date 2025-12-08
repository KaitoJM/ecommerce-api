<?php

namespace App\Http\Services;

use App\Models\Product;

class ProductService {
    /**
     * Get products for the given user with optional filters.
     *
     * @param string|null $search Optional search term to filter by name or description
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product>
     */
    public function getProducts(?string $search = null) {
        $query = Product::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

    /**
     *
     * @param  array{
     *     name: string
     *     description?: string|null
     *     price: double
     * }  $params
     * @return \App\Models\Product
     */
    public function createProduct($params) {
        $createdProduct = Product::create([
            'name' => $params['name'],
            'description' => $params['description'] ?? '',
            'price' => $params['price']
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
        return Product::findOrFail($id);
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
}