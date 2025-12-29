<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = $this->migrateCustomers();
        $carts = $this->migrateCarts($customers);
        $this->migrateOrders($carts);
    }

    private function migrateCustomers() {
        $user1 = User::factory()->create(['role' => 'customer']);
        $user2 = User::factory()->create(['role' => 'customer']);

        $customer1 = Customer::factory()->create(['user_id' => $user1->id]);
        $customer2 = Customer::factory()->create(['user_id' => $user2->id]);

        return [$customer1, $customer2];
    }

    private function getRandomProducts() {
        $products = Product::inRandomOrder()
            ->limit(3)
            ->get();

        return $products;
    }

    private function migrateCarts($customers) {
        $carts = [];
        foreach ($customers as $key => $customer) {
            $cart = Cart::factory()->create([
                'customer_id' => $customer->id
            ]);

            $products = $this->getRandomProducts();

            foreach ($products as $productKey => $product) {
                $this->migrateCartItems($cart->id, $product->id);
            }

            $carts[] = $cart;
        }

        return $carts;
    }

    private function migrateCartItems($cartId, $productId) {
        $specification = ProductSpecification::where('product_id', $productId)->inRandomOrder()->first();

        CartItem::factory()->create([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'product_specification_id' => $specification->id,
            'quantity' => rand(1,3)
        ]);
    }

    private function migrateOrders($carts) {
        foreach ($carts as $cartKey => $cart) {
            $status = OrderStatus::first();

            $cartItems = CartItem::where('cart_id', $cart->id)->get();
            $orderItems = [];
            $total = 0;

            foreach ($cartItems as $cartItemKey => $cartItem) {
                $product = Product::find($cartItem->product_id);
                $specification = ProductSpecification::find($cartItem->product_specification_id);
                $itemTotal = $cartItem->quantity * $specification->price;

                $orderItems[] = [
                    'product_id' => $cartItem->product_id,
                    'product_specification_id' => $cartItem->product_specification_id,
                    'product_snapshot_name' => $product->name,
                    'product_snapshot_price' => $specification->price,
                    'quantity' => $cartItem->quantity,
                    'total' => $itemTotal
                ];

                $total+= $itemTotal;
            }


            $order = Order::factory()->create([
                'cart_id' => $cart->id,
                'customer_id' => $cart->customer_id,
                'status_id' => $status->id,
                'subtotal' => $total,
                'discount_total' => 0,
                'tax_total' => 0,
                'total' => $total
            ]);

            foreach ($orderItems as $key => $orderItem) {
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $orderItem['product_id'],
                    'product_specification_id' => $orderItem['product_specification_id'],
                    'product_snapshot_name' => $orderItem['product_snapshot_name'],
                    'product_snapshot_price' => $orderItem['product_snapshot_price'],
                    'quantity' => $orderItem['quantity'],
                    'total' => $orderItem['total']
                ]);
            }
        }
    }
}
