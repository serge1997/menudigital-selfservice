<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoResource extends JsonResource
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
            'ped_customerName' => $this->ped_customerName,
            'ped_emissao' => $this->ped_emissao,
            'ped_tableNumber' => $this->ped_tableNumber,
            'status_id' => $this->status_id,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'status_id' => $this->status_id,
            'stat_desc' => $this->status->stat_desc,
            'itens' => ItensPedidoResource::collection($this->item),
        ];
    }
}
