<?php

namespace App\Services\Site\CartItem;

use App\Repositories\CartItemRepository;
use App\Services\Site\CartItem\Validation\AddToCartContext;

class CartItemService {
    /**
     * @param AddToCartRule[] $rules
     */
    public function __construct(
        protected CartItemRepository $cartItemRepository,
        private iterable $rules
    ) {}

    public function addToCart(string $cartId, string $productId, string $productSpecificationId, int $qty) {
        $context = new AddToCartContext(
            $cartId,
            $productId,
            $productSpecificationId,
            $qty
        );

        foreach ($this->rules as $rule) {
            $rule->validate($context);
        }

        return $this->cartItemRepository->createCartItem([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'product_specification_id' => $productSpecificationId,
            'quantity' => $qty,
        ]);
    }
}
