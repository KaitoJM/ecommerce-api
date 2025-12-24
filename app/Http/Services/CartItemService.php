<?php

namespace App\Http\Services;

use App\Models\CartItem;

class CartItemService {
    public function getCartItems(?string $search = null, $filters = null, $pagination = null) {
        return CartItem::when($search, function($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('quantity', 'like', "%{$search}%");
            });
        })->when(isset($filters['cart_id']), function($query) use ($filters) {
            $query->where('cart_id', $filters['cart_id']);
        })->when(isset($filters['product_id']), function($query) use ($filters) {
            $query->where('product_id', $filters['product_id']);
        })->when(isset($filters['product_specication_id']), function($query) use ($filters) {
            $query->where('product_specication_id', $filters['product_specication_id']);
        })->paginate($pagination['per_page'] ?? 10);
    }

    /**
     *
     * @param  array{
     *     cart_id: integer
     *     product_id: integer
     *     product_specication_id: integer
     *     quantity: integer
     * }  $params
     * @return \App\Models\CartItem
     */
    public function createCartItem($params) {
        $createdCartItem = CartItem::create([
            'cart_id' => $params['cart_id'],
            'product_id' => $params['product_id'],
            'product_specication_id' => $params['product_specication_id'] ?? '',
            'quantity' => $params['quantity'] ?? 1,
        ]);

        return $createdCartItem;
    }

    /**
     * Get a cart item by its ID.
     *
     * @param int $id The ID of the cart item to get
     * @return \App\Models\CartItem
     */
    public function getCartItemById(int $id) {
        return CartItem::findOrFail($id);
    }

    /**
     * Update a cart item by its ID.
     *
     * @param int $id The ID of the cart item to update
     * @param array $params The parameters to update the cart item with
     * @return \App\Models\CartItem
     */
    public function updateCartItem(int $id, array $params) {
        $cartItem = $this->getCartItemById($id);

        $cartItem->update($params);

        return $cartItem;
    }

    /**
     * Delete a cart item by its ID.
     *
     * @param int $id The ID of the cart item to delete
     * @return \App\Models\CartItem
     */
    public function deleteCartItem(int $id) {
        $cartItem = $this->getCartItemById($id);

        $cartItem->delete();

        return $cartItem;
    }
}
