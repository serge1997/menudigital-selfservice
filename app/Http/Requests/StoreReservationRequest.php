<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "person_quantity"     => ["required"],
            "date_come_in"        => ["required"],
            "hour"                => ["required"],
            "customer_firstName"  => ["required"],
            "customer_lastName"   => ["required"],
            "customer_tel"        => ["required"],
            "reser_canal"         => ["required"],
            "observation"         => ["required"]
        ];
    }

    public function messages(): array
    {
        return [
            "person_quantity.required"     => "quantidade de pessoa é obrigatório",
            "date_come_in.required"        => "data é obrigatório",
            "hour.required"                => "horario obrigatório",
            "customer_firstName.required"  => "nobrenome obrigatório",
            "customer_lastName.required"   => "nome obrigatório",
            "customer_tel.required"        => "telefone é obrigatório",
            "reser_canal.required"         => "canal de reservação é obrigatório",
            "observation.required"         => "observaçã é obrigatório"
        ];
    }
}
