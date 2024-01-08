<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockEntryRequest extends FormRequest
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
            'productID'      => ['required'],
            'supplierID'     => ['required'],
            'requisition_id' => ['required'],
            'quantity'       => ['required'],
            'unitCost'       => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'productID.required'  => 'produto é obrigatório',
            'requisition_id.required' => 'numero da requisition é obrigatório',
            'supplierID.required' => 'fornecedor é obrigatório',
            'quantity.required'   => 'quantidade é obrigatório',
            'unitCost.required'   => 'custo é obrigatório'
        ];
    }
}
