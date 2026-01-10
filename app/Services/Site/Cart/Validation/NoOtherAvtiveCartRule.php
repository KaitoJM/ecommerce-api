<?php

namespace App\Services\Site\Cart\Validation;

use App\Repositories\CartRepository;
use InvalidArgumentException;

class NoOtherActiveCartRule implements AddCartRule
{
    public function __construct(
        private CartRepository $repository
    ) {}

    public function validate(AddCartContext $context): void
    {
        $cart = $this->repository->getCarts(null, [
            'customer_id' => $context->customerId,
            'status' => $context->status,
        ]);

        if ($cart->count()) {
            throw new InvalidArgumentException(
                'Active cart already exist.'
            );
        }
    }
}
