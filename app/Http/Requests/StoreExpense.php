<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Models\Role;

class StoreExpense extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        $authUser = $request->session()->get('auth-vue');
        if ($authUser == Role::CAN_CREATE_PRODUCT || Role::MANAGER){
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'item_id' => ["required_if:product_id,null"],
            'product_id' => ["required_if:item_id,null"],
            'quantity' => ['required'],
            'observation' => ['required']
        ];
    }
}
