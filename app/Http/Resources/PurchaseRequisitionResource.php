<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseRequisitionResource extends JsonResource
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
            'created_at'  => $this->created_at->format('d/m/Y'),
            'delivery_date' => $this->delivery_date->format('d/m/Y'),
            'department_id' => $this->department_id,
            'status_id' => $this->status_id,
            'observation' => $this->observation,
            'requisition_code' => $this->requisition_code,
            'response_date' => $this->response_date
        ];
    }
}
