<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\StockEntry;

class ExpenseResource extends JsonResource
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
            'item_id' => $this->item_id ?? "Não encontrado",
            'item_name' => $this->item->item_name ?? "Não encontrado",
            'product_id' => $this->product_id,
            'prod_name' => $this->product->prod_name,
            'totalCost' => $this->total_cost,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'observation' => $this->observation,
            'quantity' => $this->quantity,
            'emissao' => date('d/m/Y', strtotime($this->created_at))
        ];
    }
}
