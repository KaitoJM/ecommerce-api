<?php

namespace App\Services\Site\OrderItem\Contexts;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSpecification;

class AddOrderItemContext
{
    public function __construct(
        public Order $order,
        public Product $product,
        public ProductSpecification $specification,
        public string $quantity,
    ) {}

    public array $params;
}
