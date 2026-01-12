<?php

namespace App\Services\Site\Order\Contexts;

use App\Models\Order;
use Illuminate\Support\Collection;

class AddOrderContext
{
    public function __construct(
        public array $params,
        public Collection $items,
        public ?string $customerId,
    ) {}

    public Collection $itemsModelled;
    public float $subtotal = 0;
    public float $discount_total = 0;
    public float $tax_total = 0;
    public float $total = 0;
    public string $status_id = '';
    public ?Order $order = null;
}
