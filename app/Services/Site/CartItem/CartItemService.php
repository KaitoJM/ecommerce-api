<?php

namespace App\Services\Site\CartItem;

use App\Repositories\CartItemRepository;
use App\Services\Site\CartItem\Pipelines\AddToCartPipeline;
use App\Services\Site\CartItem\Validation\AddToCartContext;

class CartItemService {
    public function __construct(
        protected CartItemRepository $cartItemRepository,
        private AddToCartPipeline $addToCartPipeline
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

        $this->addToCartPipeline->validate($context);

        return $this->cartItemRepository->createCartItem([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'product_specification_id' => $productSpecificationId,
            'quantity' => $qty,
        ]);
    }

    public function updateCartItem(string $id, $params) {
        return $this->cartItemRepository->updateCartItem($id, $params);
    }

    public function deleteCartItem(string $id) {
        return $this->cartItemRepository->deleteCartItem($id);
    }
}
