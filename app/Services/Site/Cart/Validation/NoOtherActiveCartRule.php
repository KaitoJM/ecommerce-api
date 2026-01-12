<?php

namespace App\Services\Site\Cart\Validation;

use App\Repositories\CartRepository;
use Closure;
use Illuminate\Validation\ValidationException;

class NoOtherActiveCartRule
{
    public function __construct(
        private CartRepository $repository
    ) {}

    public function handle(AddCartContext $context, Closure $next)
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

        return $next($context);
    }
}
