<?php

namespace App\Services\Site\OrderItem\Pipes\CreateOrderItem;

use App\Repositories\ProductSpecificationRepository;
use App\Services\Site\OrderItem\Contexts\AddOrderItemContext;
use Closure;
use InvalidArgumentException;

class CheckAndUpdateStockAvailabilityPipe
{
    public function __construct(
        private ProductSpecificationRepository $repository
    ) {}

    public function handle(AddOrderItemContext $context, Closure $next) {
        if ($context->specification->stock < $context->quantity) {
            throw new InvalidArgumentException(
                'Not enough stock for product specification with id #' . $context->specification->id
            );
        }

        // Update Stock
        $this->repository->updateProductSpecification(
            $context->specification->id,
            [
                'stock' => $context->specification->stock - $context->quantity
            ]
        );

        return $next($context);
    }
}
