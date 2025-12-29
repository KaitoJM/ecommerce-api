<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository {
    /**
     * Get categories with optional filters.
     *
     * @param string|null $search Optional search term to filter by name or description
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category>
     */
    public function getCategories(?string $search = null) {
        return Category::search($search)
            ->paginate($pagination['per_page'] ?? 10);
    }

    /**
     *
     * @param  array{
     *     name: string
     * }  $params
     * @return \App\Models\Category
     */
    public function createCategory($params) {
        $createdCategory = Category::create([
            'name' => $params['name'],
            'description' => $params['description'] ?? '',
        ]);

        return $createdCategory;
    }

    /**
     * Get a category by its ID.
     *
     * @param int $id The ID of the category to get
     * @return \App\Models\Category
     */
    public function getCategoryById(int $id) {
        return Category::findOrFail($id);
    }

    /**
     * Update a category by its ID.
     *
     * @param int $id The ID of the category to update
     * @param array $params The parameters to update the category with
     * @return \App\Models\Category
     */
    public function updateCategory(int $id, array $params) {
        $category = $this->getCategoryById($id);

        $category->update($params);

        return $category;
    }

    /**
     * Delete a category by its ID.
     *
     * @param int $id The ID of the category to delete
     * @return \App\Models\Category
     */
    public function deleteCategory(int $id) {
        $category = $this->getCategoryById($id);

        $category->delete();

        return $category;
    }
}
