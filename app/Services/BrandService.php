<?php

namespace App\Services;

use App\Models\Brand;

class BrandService {
    /**
     * Get brands with optional filters.
     *
     * @param string|null $search Optional search term to filter by name or description
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Brand>
     */
    public function getBrands(?string $search = null) {
        $query = Brand::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->paginate($pagination['per_page'] ?? 10);
    }

    /**
     *
     * @param  array{
     *     name: string
     * }  $params
     * @return \App\Models\Brand
     */
    public function createBrand($params) {
        $createdBrand = Brand::create([
            'name' => $params['name'],
            'image' => $params['image'] ?? '',
        ]);

        return $createdBrand;
    }

    /**
     * Get a brand by its ID.
     *
     * @param int $id The ID of the brand to get
     * @return \App\Models\Brand
     */
    public function getBrandById(int $id) {
        return Brand::findOrFail($id);
    }

    /**
     * Update a brand by its ID.
     *
     * @param int $id The ID of the brand to update
     * @param array $params The parameters to update the brand with
     * @return \App\Models\Brand
     */
    public function updateBrand(int $id, array $params) {
        $brand = $this->getBrandById($id);

        $brand->update($params);

        return $brand;
    }

    /**
     * Delete a brand by its ID.
     *
     * @param int $id The ID of the brand to delete
     * @return \App\Models\Brand
     */
    public function deleteBrand(int $id) {
        $brand = $this->getBrandById($id);

        $brand->delete();

        return $brand;
    }
}
