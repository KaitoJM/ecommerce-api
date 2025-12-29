<?php

namespace App\Repositories;

use App\Models\Cart;
use Carbon\Carbon;

class CartRepository {
    /**
     * Get carts with optional filters.
     *
     * @param string|null $search Optional search term to filter by name or description
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cart>
     */
    public function getCarts(?string $search = null, $filters = null, $pagination = null) {
        return Cart::search($search)
        ->filterStatus($filters['status'] ?? null)
        ->filterExpiresAt($filters['expires_at'] ?? null)
        ->paginate($pagination['per_page'] ?? 10);
    }

    /**
     *
     * @param  array{
     *     name: string
     * }  $params
     * @return \App\Models\Cart
     */
    public function createCart($params) {
        $createdCart = Cart::create([
            'customer_id' => $params['customer_id'] ?? null,
            'session_id' => $params['session_id'] ?? null,
            'status' => $params['status'] ?? 'active',
            'expires_at' => Carbon::now()->addMonths(6),
        ]);

        return $createdCart;
    }

    /**
     * Get a cart by its ID.
     *
     * @param int $id The ID of the cart to get
     * @return \App\Models\Cart
     */
    public function getCartById(int $id) {
        return Cart::findOrFail($id);
    }

    /**
     * Update a cart by its ID.
     *
     * @param int $id The ID of the cart to update
     * @param array $params The parameters to update the cart with
     * @return \App\Models\Cart
     */
    public function updateCart(int $id, array $params) {
        $cart = $this->getCartById($id);

        $cart->update($params);

        return $cart;
    }

    /**
     * Delete a cart by its ID.
     *
     * @param int $id The ID of the cart to delete
     * @return \App\Models\Cart
     */
    public function deleteCart(int $id) {
        $Cart = $this->getCartById($id);

        $Cart->delete();

        return $Cart;
    }
}
