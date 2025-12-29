<?php

namespace App\Repositories;

use App\Models\CartItem;

class CartItemRepository {
    public function getCartItems(?string $search = null, $filters = null, $pagination = null) {
        return CartItem::search($search)
        ->filterCartId($filters['cart_id'] ?? null)
        ->filterProductId($filters['product_id'] ?? null)
        ->filterProductSpecificationId($filters['product_specification_id'] ?? null)
        ->paginate($pagination['per_page'] ?? 10);
    }

    /**
     *
     * @param  array{
     *     cart_id: integer
     *     product_id: integer
     *     product_specification_id: integer
     *     quantity: integer
     * }  $params
     * @return \App\Models\CartItem
     */
    public function createCartItem($params) {
        $createdCartItem = CartItem::create([
            'cart_id' => $params['cart_id'],
            'product_id' => $params['product_id'],
            'product_specification_id' => $params['product_specification_id'] ?? '',
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
