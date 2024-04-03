<?php

namespace App\Http\Requests;

use App\Rules\EmailValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
//use App\Rules\EmailValidation;

class RestaurantFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
        $authUser = $request->session()->get('auth-vue');
        if (!is_null($authUser)){
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
            'rest_name'          => ['required'],
            'rest_email'         => ['required', new EmailValidation],
            'rest_cnpj'         => ['required'],
            'res_city'          => ['required'],
            'res_neighborhood'  => ['required'],
            'rest_streetName'   => ['required'],
            'rest_StreetNumber' => ['required'],
            'res_open'          => ['required'],
            'res_close'         => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'rest_name.required'          => 'nome obrigatório',
            'rest_email.required'         => 'email obrigatório',
            'rest_cnpj.required'         => 'cnpj obrigatório',
            'res_city.required'          => 'cidade obrigatório',
            'res_neighborhood.required'  => 'bairro obrigatório',
            'rest_streetName.required'   => 'rua obrigatório',
            'rest_StreetNumber.required' => 'numero obrigatório',
            'res_open.required'          => 'hora obrigatório',
            'res_close.required'         => 'hora obrigatório'
        ];
    }
}
