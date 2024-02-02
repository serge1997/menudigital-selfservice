<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Services\UserRoleInstance;
use App\Main\UserRole\UserRoleRepositoryInterface;
use App\Main\User\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;



class UserRoleController extends Controller
{
    protected UserRoleRepositoryInterface $userRoleRepositoryInterface;
    protected UserRepositoryInterface $userRepositoryInterface;

    public function __construct(
        UserRoleRepositoryInterface $userRoleRepositoryInterface,
        UserRepositoryInterface $userRepositoryInterface
    )
    {
        $this->userRoleRepositoryInterface = $userRoleRepositoryInterface;
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function get_all_rules(): JsonResponse
    {
        return response()->json($this->findAll());
    }

    public function delete_user_role($id, Request $request): JsonResponse
    {
        try{
            $message = "Permissão deletada com successo";
            $this->userRoleRepositoryInterface->deleteUserRole($id, $request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function store_user_role($id, Request $request): JsonResponse
    {
        try{
            $message = "Permissão adicionada com sucesso";
            $this->userRoleRepositoryInterface->createUserRole($id, $request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }

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

    public function listUserRoles($id): JsonResponse
    {
        try{

            /** @var $employee UserRepositoryInterface  */
            $employee = $this->userRepositoryInterface->find($id);

            /** @var $roles UserRoleRepositoryInterface  */
            $roles = $this->userRoleRepositoryInterface->findRolesByUser($id);

            return response()
                ->json([
                    'employee' => $employee,
                    'withroles' => $roles
                ]);

        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
