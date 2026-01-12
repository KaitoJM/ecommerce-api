<?php

namespace App\Services\Site\OrderItem\Validation;

class AddOrderItemFromCartContext
{
    public function __construct(
        public string $specificationId,
        public string $quantity,
    ) {}
}
