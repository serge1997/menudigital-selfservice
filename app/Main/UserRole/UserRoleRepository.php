<?php
namespace App\Main\UserRole;

use App\Traits\Permission;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Services\Util\Util;
use App\Models\Role;
use App\Models\UserRole;
use Exception;

class UserRoleRepository implements UserRoleRepositoryInterface
{
    use Permission;

    public function createUserRole($id, $request): void
    {

        if ($this->can_manage($request)):
            $user_role = new UserRole();
            $user_role->user_id = $request->user_id;
            $user_role->role_id = $id;
            $user_role->save();
        else:
            throw new Exception(Util::PermisionExceptionMessage());
        endif;
    }

    public function deleteUserRole($id, $request): void
    {
        if ($this->can_manage($request)):
            DB::table('user_roles')
                ->where([['user_id', $request->user_id], ['role_id', $id]])
                    ->delete();
        else:
            throw new Exception(Util::PermisionExceptionMessage());
        endif;

    }

    public function findRolesByUser($id): Collection
    {
        return new Collection (
            DB::select('SELECT * FROM roles
                LEFT JOIN user_roles
                    ON roles.id = user_roles.role_id
                AND user_roles.user_id = :id', ['id' => $id]
            )
        );
    }
}
