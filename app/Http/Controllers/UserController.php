<?php

namespace App\Http\Controllers;

use App\Http\Services\UserRoleInstance;
use App\Models\Role;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreUserRequest;
use App\Models\ItensPedido;
use App\Http\Services\UserRoleAcess;
use App\Http\Services\UserInstance;
use App\Main\User\UserRepositoryInterface;
use App\Events\CancelOrder;


class UserController extends Controller
{
    protected $user;
    protected $auth_user;
    CONST USER_ID = 1;
    CONST IS_CANCEL = true;
    protected UserRepositoryInterface $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function getGroup()
    {
        $departments = DB::table('departments')
            ->select('*')
                ->get();

        $positions = DB::table('positions')
            ->get();

        return response()->json([
            'departments' => $departments,
            'positions'   => $positions
        ]);
    }

    public function create(StoreUserRequest $request)
    {


        try {

            $message = "Usuario criado com sucesso";
            $this->userRepositoryInterface->create($request);
            return response()->json($message);

        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);
        try {
            $user = User::where([['username', $request->username], ['isactive', true]])->first();

            if (!$user->username || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'msgerr' => ['senha ou usuario incoreto']
                ]);
            }
            $sess = $request->session()->put('auth-vue', $user->id);
            $this->session = $sess;
            return $user->createToken('browser')->plainTextToken;
        }catch(Exception $e){
            return response()->json('senha ou usuario incoreto.', 400);
        }
    }

    public function logout(Request $request):void
    {
        $request->session()->forget('auth-vue');
        $request->user()->currentAccessToken()->delete();
    }

    public function EditOrderStat(Request $request, $item_pedido)
    {

        $request->validate([
            'password' => ['required']
        ]);
        $auth = $request->session()->get('auth-vue');

        $password = User::where('id', $auth)
            ->first();
        if ($request->status_id !== 5 && $request->status_id !== 6):
            if (Hash::check($request->password, $password->password)):
                foreach (UserInstance::get_user_roles($auth) as $cancel):
                    if ($cancel->role_id == Role::MANAGER || $cancel->role_id == Role::CAN_CHANGE_PAIEMENT_METHOD):
                        DB::table('pedidos')
                            ->where('id', $item_pedido)
                                ->update([
                                    'status_id' => $request->status_id
                                ]);
                        return response()
                                ->json("Modo de pagamento editado com sucesso ! ",200);
                    endif;
                endforeach;
            endif;
        else:
            return response()
                ->json("This status is not allowed for this action !",422);
        endif;
        return response()
            ->json("you haven't permission!",422);
    }

    public function getEmployee(): JsonResponse
    {
        $employee = DB::table("users")
            ->select(
                "users.id",
                "users.name",
                "users.email",
                "users.tel",
                "positions.name AS position",
                "positions.id as position_id"
            )
                ->join("positions", "users.position_id", "=", "positions.id")
                    ->where('isactive', true)
                        ->get();

        return response()->json($employee);
    }

    public function getToUpdateEmployee($id): JsonResponse
    {
        $employee = User::where('id', $id)->get();
        $user_roles = UserRoleController::user_with_roles($id);
        return response()
            ->json([
                'employee' => $employee,
                'withroles' => $user_roles
            ]);
    }

    public function ToDeleteEmployee($id, Request $request): JsonResponse
    {
        $user_auth = $request->session()->get('auth-vue');
        $roles = UserInstance::get_user_roles($user_auth);
        foreach ($roles as $delete){
            if ($delete->role_id === Role::MANAGER):
                DB::table('users')
                    ->where('id', $id)
                    ->update([
                        'isactive' => false
                    ]);
                return response()
                    ->json("Usuario deletado com sucesso");
            endif;
        }
        return response()
            ->json("You don't have permission");

    }

    public function updateEmployeeStatus($id, $group_id, Request $request): JsonResponse
    {

        $authID = UserInstance::AuthUser($request);
        foreach (UserInstance::get_user_roles($authID) as $edituser):
            if ($edituser->role_id == Role::MANAGER):
                DB::table("users")
                    ->where("id", $id)
                    ->update([
                        "position_id" => $group_id
                    ]);

                return response()
                    ->json("Hieraquia editada com sucesso");
            endif;
        endforeach;

        return response()->json("You dont have permission", 500);

    }

    public function EmployeUpdate(Request $request): JsonResponse
    {
        $request->validate([
            "user_name" => ["required","string"],
            "user_email" => ["required"],
            "user_tel" => ["required"],
            "user_id" => ["required"]
        ],
        [
            "user_name.required" => "name required",
            "user_email.required" => "e-mail is required",
            "user_tel.required" => "phone contact is required"
        ]);

        $user_id = $request->user_id;
        $user_name = $request->user_name;
        $user_email = $request->user_email;
        $user_tel = $request->user_tel;

        try {
            $auth = $request->session()->get('auth-vue');
            foreach (UserInstance::get_user_roles($auth) as $update):
                if ($update->role_id === Role::MANAGER):
                    DB::beginTransaction();
                        DB::table('users')
                            ->where('id', $user_id)
                                ->update([
                                    "name" => $user_name,
                                    "email" => $user_email,
                                    "tel" => $user_tel
                                ]);
                    DB::commit();
                    return response()
                        ->json("Usuario editado com sucesso", 200);
                endif;
            endforeach;
            return response()
                ->json("You don't have permission", 422);
        }catch (Exception $e) {
            DB::rollBack();
            return response()->json("usuario não pode ser editado, tente novamente");
        }
    }

    public function currentUser(Request $request)
    {
        $user_id = $request->session()->get('auth-vue');
       var_dump($user_id);
    }

}
