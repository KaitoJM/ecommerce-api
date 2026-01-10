<?php

namespace App\Services\Site\Cart\Validation;

use App\Repositories\CartRepository;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class NoOtherActiveCartRule implements AddCartRule
{
    public function __construct(
        private CartRepository $repository
    ) {}

    public function validate(AddCartContext $context): void
    {
        if ($context->status == 'active') {
            $cart = $this->repository->getCarts(null, [
                'customer_id' => $context->customerId,
                'status' => $context->status,
            ]);

            if ($cart->count()) {
                throw ValidationException::withMessages([
                    'cart' => 'Active cart already exists.',
                ]);
            }
        }
    }
}
