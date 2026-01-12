<?php

namespace App\Services\Site\Cart;

use App\Repositories\CartRepository;
use App\Repositories\OrderStatusRepository;
use App\Services\Site\Cart\Validation\AddCartContext;
use App\Services\Site\Cart\Validation\NoOtherActiveCartRule;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;

class CartService {
    public function __construct(
        protected CartRepository $cartRepository,
        protected OrderStatusRepository $statusRepository,
        private Pipeline $pipeline,
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

    public function getCart(string $cartId) {
        return $this->cartRepository->getCartById($cartId);
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
        $carts = $this->getCarts($customerId, 'active');

        if (!isset($carts[0])) {
            $activeCart = $this->addCart($customerId, 'active');
        } else {
            $activeCart = $carts[0];
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

        $this->pipeline
            ->send($context)
            ->through([
                NoOtherActiveCartRule::class,
            ])
            ->thenReturn();

        return $this->cartRepository->createCart([
            'customer_id' => $customerId,
            'status' => $status,
        ]);
    }

    public function addCartAsGuest() {
        $guestToken = (string) Str::uuid();
        $statuses = $this->statusRepository->getOrderStatuses('Pending');

        return $this->cartRepository->createCart([
            'session_id' => $guestToken,
            'status' => $statuses[0]->id,
        ]);
    }
}
