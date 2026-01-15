<?php
namespace App\Services\Site\OrderItem\Pipes\CreateOrderItem;

use App\Repositories\ProductSpecificationRepository;
use Closure;
use App\Services\Site\OrderItem\Contexts\AddOrderItemContext;

class UpdateProductSpecificationPipe
{
    public function __construct(
        private ProductSpecificationRepository $repository
    ) {}

    public function handle(AddOrderItemContext $context, Closure $next) {
        $this->repository->updateProductSpecification(
            $context->specification->id,
            [
                'stock' => $context->specification->stock - $context->quantity
            ]
        );

        return $next($context);
    }
}
