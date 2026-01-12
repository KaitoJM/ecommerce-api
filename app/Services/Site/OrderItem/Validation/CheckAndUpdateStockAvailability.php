<?php

namespace App\Services\Site\OrderItem\Validation;

use App\Repositories\ProductSpecificationRepository;
use App\Services\Site\OrderItem\Contexts\AddOrderItemFromCartContext;
use Closure;
use InvalidArgumentException;

class CheckAndUpdateStockAvailability
{
    public function __construct(
        private ProductSpecificationRepository $repository
    ) {}

    public function handle(AddOrderItemFromCartContext $context, Closure $next) {
        $specification = $this->repository->getProductSpecificationById($context->specificationId);

        if ($specification->stock < $context->quantity) {
            throw new InvalidArgumentException(
                'Not enough stock for product specification with id #' . $context->specificationId
            );
        }

        // Update Stock
        $this->repository->updateProductSpecification(
            $context->specificationId,
            [
                'stock' => $specification->stock - $context->quantity
            ]
        );

        return $next($context);
    }
}
