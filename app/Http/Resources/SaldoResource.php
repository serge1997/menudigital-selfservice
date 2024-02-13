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
            'saldofinal' => $this->product->prod_unmed == 'g' || $this->product->prod_unmed == 'cl' ? number_format($this->saldoFinal / $this->product->prod_contain, 2) : $this->saldoFinal,
            'saldoinicial' => $this->product->prod_unmed == 'g' || $this->product->prod_unmed == 'cl' ? number_format($this->saldoInicial / $this->product->prod_contain, 2) : $this->saldoInicial,
            'saida' => $this->product->prod_unmed == 'g' || $this->product->prod_unmed == 'cl' ? number_format(($this->saldoInicial - $this->saldoFinal) / $this->product->prod_contain, 2) : $this->saldoInicial - $this->saldoFinal ,
            'prod_name' => $this->product->prod_name,
        ];
    }
}
