<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Services\UserRoleInstance;
use App\Http\Services\UserInstance;
use Illuminate\Support\Facades\DB;

class UserRoleController extends UserRoleInstance
{

    public function get_all_rules(): JsonResponse
    {
        return response()->json($this->findAll());
    }

    public function delete_user_role($id, Request $request): JsonResponse
    {
        $auth_user = $request->session()->get('auth-vue');
        foreach (UserInstance::get_user_roles($auth_user) as $role):
            if ($role->role_id === Role::MANAGER):
                DB::table('user_roles')
                    ->where([['user_id', $request->user_id], ['role_id', $id]])
                        ->delete();
                return response()->json("permission deleted !", 200);
            endif;
        endforeach;
        return response()->json("You don't have permission", 422);
    }

    public function store_user_role($id, Request $request): JsonResponse
    {
       $auth_user = $request->session()->get('auth-vue');
       foreach (UserInstance::get_user_roles($auth_user) as $role):
           if ($role->role_id === Role::MANAGER):
               $user_role = new UserRole();
               $user_role->user_id = $request->user_id;
               $user_role->role_id = $id;
               $user_role->save();
               return response()->json("Permission added successfully", 200);
           endif;
        endforeach;
        return response()->json("You dont have permission", 422);

    }

    public static function user_with_roles($id)
    {
        $result = "
            SELECT * FROM roles
                LEFT JOIN user_roles
                    ON roles.id = user_roles.role_id
                AND user_roles.user_id = {$id}
        ";

        return DB::select($result);
    }
}
