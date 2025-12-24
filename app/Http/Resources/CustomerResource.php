<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'phone' => $this->phone,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user'),
            'created_at' => $this->created_at,
        ];
    }
}
