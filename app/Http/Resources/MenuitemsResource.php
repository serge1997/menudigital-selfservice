<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuitemsResource extends JsonResource
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
            'item_name' => $this->item_name,
            'item_price' => $this->item_price,
            'item_rupture' => $this->item_rupture,
            'item_status' => $this->item_status,
            'is_lowstock' => $this->is_lowstock,
            'type_id' => $this->type_id,
            'desc_type' => $this->type->desc_type,
            'foto_type' => $this->type->foto_type
        ];
    }


}
