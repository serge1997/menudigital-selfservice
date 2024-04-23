<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
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
            "sup_name" => ["required", "max:40"],
            "sup_tel" => ["required"],
        ];
    }

    public function messages(): array
    {
        return [
            "sup_name.required" => "nome é obrigatorio",
            "sup_name.max" => "40 caracteres no maximo",
            "sup_tel.required" => "contato é obrigatorio"
        ];
    }
}
