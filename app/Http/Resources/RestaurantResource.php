<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
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
            'rest_name' => $this->rest_name,
            'rest_email' => $this->rest_email,
            'rest_cnpj' => $this->rest_cnpj,
            'rest_StreetNumber' => $this->rest_StreetNumber,
            'res_city' => $this->res_city,
            'res_neighborhood' => $this->res_neighborhood,
            'rest_streetName' => $this->rest_streetName,
            'res_close' => $this->res_close,
            'res_open' => $this->res_open,
            'rest_cep' => $this->rest_cep,
            'loss_margin' => $this->loss_margin * 100,
            'variable_margin' => $this->variable_margin * 100,
            'fix_margin' => $this->fix_margin * 100,
            'res_logo' => $this->res_logo
        ];
    }
}
