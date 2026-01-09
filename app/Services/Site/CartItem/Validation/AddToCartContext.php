<?php


namespace App\Services\Site\CartItem\Validation;

class AddToCartContext
{
    public function __construct(
        public string $cartId,
        public string $productId,
        public string $productSpecificationId,
        public int $quantity
    ) {}
}
