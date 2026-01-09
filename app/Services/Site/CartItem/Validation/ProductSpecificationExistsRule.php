<?php

namespace App\Services\Site\CartItem\Validation;

use App\Repositories\ProductSpecificationRepository;
use OutOfBoundsException;

class ProductSpecificationExistsRule implements AddToCartRule
{
    public function __construct(
        private ProductSpecificationRepository $repository
    ) {}

    public function validate(AddToCartContext $context): void
    {
        if (! $this->repository->getProductSpecificationById($context->productSpecificationId)) {
            throw new OutOfBoundsException(
                'The provided product specification ID is not valid.'
            );
        }
    }
}
