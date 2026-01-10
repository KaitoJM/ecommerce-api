<?php


namespace App\Services\Site\CartItem\Pipelines;

use App\Services\Site\CartItem\Validation\AddToCartContext;
use App\Services\Site\CartItem\Validation\AddToCartRule;

class AddToCartPipeline
{
    /**
     * @param iterable<AddToCartRule> $rules
     */
    public function __construct(
        private iterable $rules
    ) {}

    public function validate(AddToCartContext $context): void
    {
        foreach ($this->rules as $rule) {
            $rule->validate($context);
        }
    }
}
