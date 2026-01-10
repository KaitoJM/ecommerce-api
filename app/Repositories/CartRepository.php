<?php

namespace App\Repositories;

use App\Models\Cart;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class CartRepository {
    public function buildCartQuery(?string $search = null, $filters = null):Builder {
        return Cart::search($search)
        ->filterStatus($filters['status'] ?? null)
        ->filterCustomer($filters['customer_id'] ?? null)
        ->filterExpiresAt($filters['expires_at'] ?? null);
    }

    /**
     * Get carts with optional filters.
     *
     * @param string|null $search Optional search term to filter by name or description
     * @param {
     *  status?: string,
     *  expires_at?: string,
     *  customer_id?: string
     * } $filters
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cart>
     */
    public function getCarts(?string $search = null, $filters = null) {
        return $this->buildCartQuery($search, $filters)
            ->get();
    }

    /**
     * Get paginated carts with optional filters.
     *
     * @param string|null $search Optional search term to filter by name or description
     * @param {
     *  status?: string,
     *  expires_at?: string,
     *  customer_id?: string
     * } $filters
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cart>
     */
    public function getPaginatedCarts(?string $search = null, $filters = null, $pagination = null) {
        return $this->buildCartQuery($search, $filters)
            ->paginate($pagination['per_page'] ?? 10);
    }

    /**
     *
     * @param  array{
     *     customer_id?: string,
     *     session_id?: string,
     *     status: string,
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
