<?php

namespace App\Http\Services;

use App\Models\Attribute;

class AttributeService {
    /**
     * Get attributes with optional filters.
     *
     * @param string|null $search Optional search term to filter by name or description
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attribute>
     */
    public function getAttributes(?string $search = null) {
        $query = Attribute::query();

        if ($search) {
            $query->where('attribute', 'like', "%{$search}%");
        }

        return $query->paginate($pagination['per_page'] ?? 10);
    }

    /**
     *
     * @param  array{
     *     attribute: string
     *     selection_type: string
     * }  $params
     * @return \App\Models\Attribute
     */
    public function createAttribute($params) {
        $createdAttribute = Attribute::create($params);

        return $createdAttribute;
    }

    /**
     * Get a attribute by its ID.
     *
     * @param int $id The ID of the attribute to get
     * @return \App\Models\Attribute
     */
    public function getAttributeById(int $id) {
        return Attribute::findOrFail($id);
    }
    
    /**
     * Update a attribute by its ID.
     *
     * @param int $id The ID of the attribute to update
     * @param array $params The parameters to update the attribute with
     * @return \App\Models\Attribute
     */ 
    public function updateAttribute(int $id, array $params) {
        $attribute = $this->getAttributeById($id);

        $attribute->update($params);

        return $attribute;
    }
    
    /**
     * Delete an attribute by its ID.
     *
     * @param int $id The ID of the attribute to delete
     * @return \App\Models\Attribute
     */
    public function deleteAttribute(int $id) {
        $attribute = $this->getAttributeById($id);

        $attribute->delete();

        return $attribute;
    }
}