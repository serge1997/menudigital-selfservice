<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequisitionRequest extends FormRequest
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
            'products_id'     => ['required'],
            'quantity'       => ['required'],
            'delivery_date'  => ['required'],
            'department_id'  => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'produto obrigatório',
            'quantity.required' => 'quantidade obrigatório',
            'delivery_date.required' => 'Data obrigatório',
            'department_id.required' => 'departamento obrigatório'
        ];
    }
}
