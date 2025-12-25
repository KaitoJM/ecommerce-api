<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'customer' => $this->whenLoaded('customer'),
            'session_id' => $this->session_id,
            'cart_id' => $this->cart_id,
            'status_id' => $this->status_id,
            'status' => $this->whenLoaded('status'),
            'subtotal' => $this->subtotal,
            'discount_total' => $this->discount_total,
            'tax_total' => $this->tax_total,
            'total' => $this->total,
            'created_at' => $this->created_at,
        ];
    }
}
