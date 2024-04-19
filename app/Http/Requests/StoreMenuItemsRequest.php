<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemsRequest extends FormRequest
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
            'item_name' => ['required', 'max:40'],
            'item_price' => ['required'],
            'item_status' => ['required'],
            'type_id' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'item_name.required'   => "name is required",
            'item_name.max'        => '40 caractere maximum',
            'item_price'           => "price is required",
            'item_status.required' => 'status is required',
            'type_id.required'     => 'type is required'
        ];
    }
}
