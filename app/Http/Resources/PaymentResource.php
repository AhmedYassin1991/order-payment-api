<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'payment_id' => $this->payment_id,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'), // Format the date
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'), // Format the date
        ];
    }
}
