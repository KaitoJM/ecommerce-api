<?php

namespace App\Services\Site\Order\Contexts;

class AddOrderContext
{
    public function __construct(
        public string $cartId,
    ) {}
}
