<?php

namespace App\Services\Site\Cart;

use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Services\Site\Cart\Pipelines\AddCartPipeline;
use App\Services\Site\Cart\Validation\AddCartContext;
use App\Services\Site\Cart\Validation\AddCartRule;

class OrderService {
    public function __construct(
        protected OrderRepository $orderRepository,
        private AddCartPipeline $addCartPipeline
    ) {}

    public function createOrder($params, $items) {
        // validate items stock


        return $this->orderRepository->createOrder($params);
    }
}
