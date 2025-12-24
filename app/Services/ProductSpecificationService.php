<?php

namespace App\Services;

use App\Models\ProductSpecification;

class ProductSpecificationService {
    /**
     * Get product specifications.
     *
     * @param int $product_id The ID of the product where the specification belongs
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductSpecification>
     */
    public function getProductSpecifications($filters = null) {
        $query = ProductSpecification::query();

        if ($filters['product_id'] ?? false) {
            $query->where('product_id', $filters['product_id']);
        }

        if ($filters['default'] ?? false) {
            $query->where('default', $filters['default']);
        }

        if ($filters['sale'] ?? false) {
            $query->where('sale', $filters['sale']);
        }

        return $query->get();
    }

    /**
     * Save new product specification value
     * @param int $product_id The product the attribute value will be belonged to
     * @param string $combination A comma separated IDs of product attributes of the referenced product
     * @param float $price The price of the product
     * @param boolean $default The indicator of the default specification of the referenced product
     * @param boolean $sale Flag if this product specification is sale
     * @param float $sale_price The price of the product if set to sale
     * @return \App\Models\ProductSpecification
     */
    public function createProductSpecification(
        $product_id,
        $combination,
        $price,
        $stock,
        $default = false,
        $sale = false,
        $sale_price = 0,
        $images = ''
    ) {
        $createdProductAttribute = ProductSpecification::create([
            'product_id' => $product_id,
            'combination' => $combination,
            'price' => $price,
            'stock' => $stock,
            'default' => $default,
            'sale' => $sale,
            'sale_price' => $sale_price,
            'images' => $images,
        ]);

        return $createdProductAttribute;
    }

    /**
     * Get a product specification by its ID.
     *
     * @param int $id The ID of the product specification to get
     * @return \App\Models\ProductSpecification
     */
    public function getProductSpecificationById(int $id) {
        return ProductSpecification::findOrFail($id);
    }

    /**
     * Update a product specification by its ID.
     *
     * @param int $id The ID of the product specification to update
     * @param array $params The parameters to update the product attribute with
     * @return \App\Models\ProductSpecification
     */
    public function updateProductSpecification(int $id, $params) {
        $product = $this->getProductSpecificationById($id);

        $product->update($params);

        return $product;
    }

    /**
     * Delete a product specification by its ID.
     *
     * @param int $id The ID of the product specification to delete
     * @return \App\Models\ProductSpecification
     */
    public function deleteProductSpecification(int $id) {
        $productAttribute = $this->getProductSpecificationById($id);

        $productAttribute->delete();

        return $productAttribute;
    }

    public function getProductDefaultSpecification(int $product_id) {
        $default = ProductSpecification::where('product_id', $product_id)
            ->where('default', true)
            ->first();

        if (!$default) {
            $default = $this->createProductSpecification($product_id, '', 0, 0, true);
        }

        return $default;
    }

    public function deleteProductSpecifications(int $product_id) {
        ProductSpecification::where('product_id', $product_id)->delete();
    }
}
