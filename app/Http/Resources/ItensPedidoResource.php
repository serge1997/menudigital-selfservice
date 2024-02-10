<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItensPedidoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'item_pedido' => $this->item_pedido,
            'item_comments' => $this->item_comments,
            'item_option' => $this->item_option,
            'item_id' => $this->item_id,
            'item_quantidade' => $this->item_quantidade,
            'item_price' => $this->item_price,
            'item_total' => $this->item_total,
            'item_emissao' => $this->item_emissao,
            'item_name' => $this->menuitem->item_name,
        ];
    }
}
