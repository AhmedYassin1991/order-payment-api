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
            'user_id' => $this->user_id,
            'items' => $this->items,
            'total' => $this->total,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'), // Format the date
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'), // Format the date
        ];
    }
}
