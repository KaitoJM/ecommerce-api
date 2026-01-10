<?php

namespace App\Services\Site\Cart\Validation;

interface AddCartRule
{
    public function validate(AddCartContext $context): void;
}
