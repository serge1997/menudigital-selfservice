<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Services\UserRoleInstance;
use Illuminate\Support\Facades\DB;

class UserRoleController extends UserRoleInstance
{

    public function get_all_rules(): JsonResponse
    {
        return response()->json($this->findAll());
    }

    public function update_user_roles($id, Request $request): JsonResponse
    {
        DB::table('user_roles')
            ->where([['user_id', $request->user_id], ['role_id', $id]])
                ->delete();

        return response()->json("permission deleted !");
    }

    public function update_user_roles_add($id, Request $request): JsonResponse
    {
       $user_role = new UserRole();
       $user_role->user_id = $request->user_id;
       $user_role->role_id = $id;
       $user_role->save();

        return response()->json("Permission added succesfully");

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
