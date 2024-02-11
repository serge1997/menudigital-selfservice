<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'prod_name' => $this->prod_name,
            'prod_contain' => $this->prod_contain,
            'min_quantity' => $this->min_quantity,
            'prod_desc' => $this->prod_desc,
            'prod_unmed' => $this->prod_unmed,
            'prod_supplierID' => $this->supplier->id,
            'supp_name' => $this->supplier->sup_name,
            'sup_tel' => $this->supplier->sup_tel,
            'sup_email' => $this->supplier->sup_email ?? "Não informado",
            'saldoFinal' => $this->saldo->saldoFinal
        ];
    }
}
