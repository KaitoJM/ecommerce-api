<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'cart_id' => $this->cart_id,
            'product_id' => $this->product_id,
            'product' => $this->whenLoaded(
                'product',
                fn () => new ProductResource($this->product)
            ),
            'product_specification_id' => $this->product_specification_id,
            'specification' => $this->whenLoaded('specification'),
            'quantity' => $this->quantity,
            'created_at' => $this->created_at,
        ];
    }
}
