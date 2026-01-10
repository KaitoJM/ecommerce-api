<?php


namespace App\Services\Site\Cart\Pipelines;

use App\Services\Site\Cart\Validation\AddCartContext;
use App\Services\Site\Cart\Validation\AddCartRule;

class AddCartPipeline
{
    /**
     * @param iterable<AddCartRule> $rules
     */
    public function __construct(
        private iterable $rules
    ) {}

    public function validate(AddCartContext $context): void
    {
        foreach ($this->rules as $rule) {
            $rule->validate($context);
        }
    }
}
