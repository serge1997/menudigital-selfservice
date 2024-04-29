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
        if ($this->isMethod('POST')){
            return [
                'prod_name' => ['required', 'max:40'],
                'prod_supplierID' => ['required'],
                'prod_unmed' => ['required'],
                'prod_contain' => ['required'],
                'min_quantity' => ['required']
            ];
        }
        if ( $this->isMethod('PUT')) {
            return [
                'prod_name' => ['required', 'max:40'],
                'prod_supplierID' => ['required'],
                'prod_contain' => ['required'],
                'min_quantity' => ['required']
            ];
        }
    }

    public function messages(): array
    {
        return [
            'prod_name.required'       => 'name is required',
            'prod_name'                => '40 caracters maximum',
            'prod_supplierID.required' => 'supplier is required',
            'prod_unmed.required'      => 'unit. measure is required',
            'prod_contain.rqeuired'    => 'unit. contain is required',
            'min_quantity.required'    => 'minimum quantity required'
        ];
    }
}
