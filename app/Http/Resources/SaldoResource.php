<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaldoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'productID' => $this->productID,
            'emissao' => $this->emissao,
            'saldofinal' => $this->saldoFinal,
            'saldoinicial' => $this->saldoInicial,
            'saida' => $this->saldoInicial - $this->saldoFinal,
            'prod_name' => $this->product->prod_name,
        ];
    }
}
