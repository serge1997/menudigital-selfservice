<?php
namespace App\Traits;

use Exception;
use App\Models\Role;
use App\Models\UserRole;

trait AuthSession
{
    public function autth($request)
    {
        $auth = $request->session()->get('auth-vue');
        return $auth;
    }

    public function checkOnlyManager($request)
    {
        $id = $this->autth($request);
        if (UserRole::where([['user_id', $id], ['role_id', Role::MANAGER]])->doesntExist()){
            throw new Exception("Você não tem permissão");
        }
    }
}
