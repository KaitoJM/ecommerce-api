<?php

namespace App\Services\Site\CartItem\Validation;

use App\Repositories\ProductSpecificationRepository;
use InvalidArgumentException;

class ProductOwnsSpecificationRule implements AddToCartRule
{
    public function __construct(
        private ProductSpecificationRepository $repository
    ) {}

    public function validate(AddToCartContext $context): void
    {
        $spec = $this->repository
            ->getProductSpecificationById($context->productSpecificationId);

        if ($spec->product_id !== $context->productId) {
            throw new InvalidArgumentException(
                'The provided product does not own this specification.'
            );
        }
    }
}
