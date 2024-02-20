<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

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
            'delivery_date' => date('d/m/Y', strtotime($this->delivery_date)),
            'department_id' => $this->department_id,
            'department_name' => $this->department->name,
            'status_id' => $this->status_id,
            'observation' => $this->observation,
            'requisition_code' => $this->requisition_code,
            'response_date' => $this->response_date,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name ?? User::find($this->user_id)->name
        ];
    }
}
