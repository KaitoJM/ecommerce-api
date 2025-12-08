<?php

namespace App\Http\Services;

use App\Models\ProductImage;

class ProductImageService {
    /**
     * Get product images with filter.
     *
     * @param int $product_id The ID of the product where the image belongs
     * @param array{
     *     cover: boolean
     * } $filters
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductImage>
     */
    public function getImages($product_id, $filters = null) {
        $query = ProductImage::where('product_id', $product_id);

        if ($filters['cover'] ?? false) {
            $query->where('cover', $filters['cover']);
        }

        return $query->get();
    }

    /**
     * Save newly uploadded product Image information
     * @param int $source The image path in the storage
     * @param int $product_id The product the image will be belonged to
     * @param boolean $cover The cover indicator. Default is false
     * @return \App\Models\ProductImage
     */
    public function createProductImage($source, $product_id, $cover = false) {
        $createdImage = ProductImage::create([
            'source' => $source,
            'product_id' => $product_id,
            'cover' => $cover
        ]);

        return $createdImage;
    }

    /**
     * Get a product image by its ID.
     *
     * @param int $id The ID of the product image to get
     * @return \App\Models\ProductImage
     */
    public function getImageById(int $id) {
        return ProductImage::findOrFail($id);
    }

    /**
     * Get a product cover image by product ID.
     *
     * @param int $id The ID of the product where the image belongs
     * @return \App\Models\ProductImage
     */
    public function getCoverImage(int $product_id) {
        return ProductImage::where('product_id', $product_id)
            ->where('cover', true)
            ->firstOrFail();
    }
    
    /**
     * Set a product image cover by its ID.
     *
     * @param int $id The ID of the product image to update
     * @param int $product_id The product ID where the images belongs to
     * @return \App\Models\ProductImage
     */ 
    public function setCoverImage(int $id, int $product_id) {
        // set all image of the product cover to false
        ProductImage::where('product_id', $product_id)
            ->update([
                'cover'=>false
            ]);
        
        // set the selected image cover to true
        $image = $this->getImageById($id);

        $image->update(['cover' => true]);

        return $image;
    }
    
    /**
     * Delete a product image by its ID.
     *
     * @param int $id The ID of the produt image to delete
     * @return \App\Models\ProductImage
     */
    public function deleteImage(int $id) {
        $image = $this->getImageById($id);

        $image->delete();

        // delete the actual file from storage

        return $image;
    }
}