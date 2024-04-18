<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EmailValidation;

class StoreUserRequest extends FormRequest
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
            'name'          => ['required', 'max:40', 'min:8'],
            'tel'           => ['required', 'max:40'],
            'email'         => ['required', new EmailValidation()],
            'password'      => ['required', 'min:5'],
            'department_id' => ['required'],
            'position_id'   => ['required'],
            'username'      => ['required', 'min:4'],
            'is_full_time' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'nome obrigatório',
            'name.max'                   => '40 caracteres no maximó',
            'name.min'                   => 'no minímo 8 caracteres',
            'tel.required'               => 'telefone obrigatório',
            'tel.max'                    => 'numero muito longo',
            'email.required'             => 'email obrigatório',
            'password.required'          => 'senha obrigatório',
            'password.min'               => 'no minímo 4 caracteres',
            'department_id.required'     => 'departamento é obrigatório',
            'position_id.required'       => 'cargo é obrigatório',
            'username.required'          => 'nome de usuário é obrigatório',
            'username.min'               => 'no minímo 4 carateres',
            'is_full_time.required'      => 'o campo taxa ou pleno é obrigatório'
        ];
    }
}
