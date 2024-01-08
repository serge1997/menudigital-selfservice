<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'name'          => ['required'],
            'tel'           => ['required'],
            'email'         => ['required'],
            'password'      => ['required'],
            'department_id' => ['required'],
            'position_id'   => ['required'],
            'username'      => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'nome obrigatório',
            'tel.required'               => 'telefone obrigatório',
            'email.required'             => 'email obrigatório',
            'password.required'          => 'senha obrigatório',
            'department_id.required'     => 'departamento é obrigatório',
            'position_id.required'       => 'cargo é obrigatório',
            'username.required'          => 'nome de usuário é obrigatório'
        ];
    }
}
