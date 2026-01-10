<?php

namespace App\Services\Site\CartItem;

use App\Repositories\CartRepository;
use App\Services\Site\Cart\Validation\AddCartContext;

class CartService {
    /**
     * @param AddCartRule[] $rules
     */
    public function __construct(
        protected CartRepository $cartRepository,
        private iterable $rules
    ) {}

    public function getCarts(string $customerId, string $status) {
        return $this->cartRepository->getCarts(null, [
            'status' => $status,
            'customer_id' => $customerId
        ]);
    }

    public function getActiveCart(string $customerId) {
        $activeCart = $this->getCarts($customerId, 'active');

        if (!$activeCart) {
            $activeCart = $this->addCart($customerId, 'active');
        }

        return $activeCart;
    }

    public function addCart(string $customerId, string $status) {
        $context = new AddCartContext(
            $customerId,
            $status,
        );

        foreach ($this->rules as $rule) {
            $rule->validate($context);
        }

        return $this->cartRepository->createCart([
            'customer_id' => $customerId,
            'status' => $status,
        ]);
    }
}
