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

    /**
     * Retrieve carts for a given customer filtered by status.
     *
     * @param string $customerId The customer identifier.
     * @param string $status     Cart status to filter by (e.g. active, completed).
     *
     * @return mixed Collection of carts matching the given criteria.
     */
    public function getCarts(string $customerId, string $status) {
        return $this->cartRepository->getCarts(null, [
            'status' => $status,
            'customer_id' => $customerId
        ]);
    }

    /**
     * Retrieve the active cart for a customer.
     *
     * If no active cart exists, a new one will be created automatically.
     *
     * @param string $customerId The customer identifier.
     *
     * @return mixed The active cart instance.
     */
    public function getOrCreateActiveCart(string $customerId) {
        $activeCart = $this->getCarts($customerId, 'active');

        if (!$activeCart) {
            $activeCart = $this->addCart($customerId, 'active');
        }

        return $activeCart;
    }

    /**
     * Create a new cart for a customer.
     *
     * This method builds an AddCartContext and executes all registered
     * cart creation rules before persisting the cart.
     *
     * @param string $customerId The customer identifier.
     * @param string $status     Initial cart status (e.g. active).
     *
     * @return mixed The newly created cart instance.
     *
     * @throws \Throwable If any cart creation rule fails.
     */
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
