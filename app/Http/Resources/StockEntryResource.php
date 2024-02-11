<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;

class StockEntryResource extends JsonResource
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
           'quantity' => $this->quantity,
           'unitCost' => $this->unitCost,
           'totalCost' => $this->totalCost,
           'supplierID' => $this->supplierID,
           'supp_name' => $this->supplier->sup_name,
           'requisition_id' => $this->requisition_id,
           'requisition_code' => $this->requisition->requisition_code,
           'prod_name' => $this->product->prod_name,
           'prod_unmed' => $this->product->prod_unmed,
           'prod_minquantity' => $this->product->min_quantity,
           'prod_contain' => $this->product->prod_contain
        ];
    }
}
