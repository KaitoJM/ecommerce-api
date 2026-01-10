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

    public function getCartItems(string $cartId) {
        return $this->cartItemRepository->getCartItems(null, [
            'cart_id' => $cartId
        ]);
    }

    /**
     * Adds a product to an existing cart.
     *
     * This method builds an AddToCartContext and runs all registered
     * cart validation rules before persisting the cart item.
     *
     * @param string $cartId                  The cart identifier.
     * @param string $productId               The product identifier.
     * @param string $productSpecificationId  The product specification identifier.
     * @param int    $qty                     Quantity to add.
     *
     * @return mixed The newly created cart item.
     *
     * @throws \Throwable If any validation rule fails.
     */
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
