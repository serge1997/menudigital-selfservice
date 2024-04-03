<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cart_id' => $this->id,
            'item_id' => $this->item_id,
            'tableNumber' => $this->tableNumber,
            'unit_price' => $this->unit_price,
            'comments' => $this->comments,
            'total' => $this->total,
            'options' => $this->options,
            'quantity' => $this->quantity,
            'item_name' => $this->item->item_name,
            'item_image' => $this->item->item_image,
            'type_id'  => $this->item->type_id,
            'item_desc' => $this->item->item_desc


        ];
    }
}
