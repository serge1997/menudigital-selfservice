<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'person_quantity' => $this->person_quantity,
            'date_come_in'   => $this->date_come_in,
            'hour'           => $this->hour,
            'observation' => $this->observation,
            'customer_firstName' => $this->customer_firstName,
            'customer_lastName' => $this->customer_lastName,
            'customer_fullname' => $this->customer_firstName." ".$this->customer_lastName,
            'customer_email' => $this->customer_email ?? "Não informado",
            'customer_tel' => $this->customer_tel,
            'reser_canal' => $this->reser_canal,
            'created_at' => $this->created_at,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name
        ];
    }
}
