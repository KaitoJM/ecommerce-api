<?php

namespace App\Services\Site\Order\Pipes\CreateOrder;

use Closure;
use App\Repositories\ProductRepository;
use App\Repositories\ProductSpecificationRepository;
use App\Services\Site\Order\Contexts\AddOrderContext;

class PrepareOrderItemsPipe
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductSpecificationRepository $specificationRepository,
    ) {}

    public function handle(AddOrderContext $context, Closure $next)
    {
        $context->itemsModelled = $context->items->map(function ($item) {
            $spec = $this->specificationRepository
                ->getProductSpecificationById($item['product_specification_id']);

            $product = $this->productRepository
                ->getProductById($item['product_id']);

            return [
                'product' => $product,
                'specification' => $spec,
                'quantity' => $item['quantity'],
                'total' => $spec->price * $item['quantity'],
            ];
        });

        return $next($context);
    }
}
