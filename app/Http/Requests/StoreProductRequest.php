<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'item_name' => ['required'],
            'item_price' => ['required'],
            'item_status' => ['required'],
            'item_desc' => ['required'],
            'type_id' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'item_name.required' => "Nome é obrigatorio",
            'item_price' => "O preço é obrigatorio"
        ];
    }
}
