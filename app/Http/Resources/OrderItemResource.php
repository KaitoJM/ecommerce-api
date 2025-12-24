<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product_specification_id' => $this->product_specification_id,
            'product_snapshot_name' => $this->product_snapshot_name,
            'product_snapshot_price' => $this->product_snapshot_price,
            'quantity' => $this->quantity,
            'total' => $this->total,
            'created_at' => $this->created_at,
        ];
    }
}
