<?php


namespace App\Services\Site\Cart\Validation;

class AddCartContext
{
    public function __construct(
        public string $customerId,
        public string $status,
    ) {}
}
