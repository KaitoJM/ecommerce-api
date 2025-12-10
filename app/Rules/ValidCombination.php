<?php

namespace App\Rules;

use App\Models\ProductAttribute;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCombination implements ValidationRule
{
    protected $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->productId) {
            return;
        }

        // Convert CSV string into array of IDs
        $ids = collect(explode(',', $value))
            ->map(fn ($id) => trim($id))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->toArray();

        // If empty or invalid input format
        if (empty($ids)) {
            $fail("The $attribute field must contain valid attribute IDs.");
            return;
        }

        // Fetch allowed attribute IDs for this product
        $validIds = ProductAttribute::where('product_id', $this->productId)
            ->pluck('id')
            ->toArray();

        // Find IDs that don't belong to this product
        $invalid = array_diff($ids, $validIds);

        if (!empty($invalid)) {
            $fail(
                "Invalid attribute IDs in $attribute: " . implode(', ', $invalid)
            );
        }
    }
}
