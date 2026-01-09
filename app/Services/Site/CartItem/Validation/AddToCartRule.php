<?php

namespace App\Services\Site\CartItem\Validation;

interface AddToCartRule
{
    public function validate(AddToCartContext $context): void;
}
