<?php

namespace App\Services\Site\CartItem\Validation;

use App\Repositories\ProductSpecificationRepository;
use Closure;
use InvalidArgumentException;

class ProductOwnsSpecificationRule
{
    public function __construct(
        private ProductSpecificationRepository $repository
    ) {}

    public function handle(AddToCartContext $context, Closure $next)
    {
        $spec = $this->repository
            ->getProductSpecificationById($context->productSpecificationId);

        if ((string)$spec->product_id !== $context->productId) {
            throw new InvalidArgumentException(
                'The provided product does not own this specification.'
            );
        }

        return $next($context);
    }
}
