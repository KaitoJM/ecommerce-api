<?php

namespace App\Repositories;

use App\Models\ProductAttribute;

class ProductAttributeService {
    /**
     * Get product attributes.
     *
     * @param int $product_id The ID of the product where the attribute belongs
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductAttribute>
     */
    public function getProductAttributes($product_id = null) {
        $query = ProductAttribute::query()->with('attribute');

        if ($product_id) {
            $query->where('product_id', $product_id);
        }

        return $query->get();
    }

    /**
     * Save new product attribute value
     * @param int $product_id The product the attribute value will be belonged to
     * @param int $attribute_id The attribute the attribute value will be belonged to
     * @param string $value The value of the attribute of the product
     * @param string $color_value The color value of the color attribute of the product if the attribute selection type is set to color
     * @return \App\Models\ProductAttribute
     */
    public function createProductAttribute($product_id, $attribute_id, $value, $color_value = null) {
        $createdProductAttribute = ProductAttribute::create([
            'product_id' => $product_id,
            'attribute_id' => $attribute_id,
            'value' => $value,
            'color_value' => $color_value,
        ]);

        return $createdProductAttribute;
    }

    /**
     * Get a product attribute by its ID.
     *
     * @param int $id The ID of the product attribute to get
     * @return \App\Models\ProductAttribute
     */
    public function getProductAttributeById(int $id) {
        return ProductAttribute::findOrFail($id);
    }

    /**
     * Update a product attribute by its ID.
     *
     * @param int $id The ID of the product attribute to update
     * @param array $params The parameters to update the product attribute with
     * @return \App\Models\ProductAttribute
     */
    public function updateProductAttribute(int $id, $params) {
        $category = $this->getProductAttributeById($id);

        $category->update($params);

        return $category;
    }

    /**
     * Delete a product attribute by its ID.
     *
     * @param int $id The ID of the product attribute to delete
     * @return \App\Models\ProductAttribute
     */
    public function deleteProductAttribute(int $id) {
        $productAttribute = $this->getProductAttributeById($id);

        $productAttribute->delete();

        return $productAttribute;
    }
}
