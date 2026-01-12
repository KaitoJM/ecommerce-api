<?php

namespace App\Services\Site\OrderItem\Contexts;

class AddOrderItemFromCartContext
{
    public function __construct(
        public string $specificationId,
        public string $quantity,
    ) {}
}
