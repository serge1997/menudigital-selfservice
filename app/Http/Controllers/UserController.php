<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreUserRequest;
use App\Models\ItensPedido;
use App\Events\CancelOrder;

class UserController extends Controller
{
    protected $user;
    CONST USER_ID = 1;
    CONST IS_CANCEL = true;

    public function __construct()
    {
        
    }

    public function getGroup()
    {
        $group = DB::table('users_group')
            ->select('*')
                ->get();

        return response()->json($group);
    }

    public function create(StoreUserRequest $request)
    {
        $request->validated();

        $values = $request->all();
        $create = new User($values);
        $create->password = Hash::make($request->password);

        try {

            DB::beginTransaction();
            $create->save();
            DB::commit();

            return response()->json("Usuario criado com sucesso !");

        }catch(Exception $e){

            echo "Usuario não pode ser cadastrado";
            DB::rollBack();
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);

        $user = User::where([['email', $request->email], ['isactive', true]])->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'msgerr' => ['senha ou usuario incoreto']
            ]);
        }

        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function CancelPermission(Request $request, $item_pedido, $item_id)
    {
        $item_quantidade = $request->quantidade;
        $password = User::where('id', self::USER_ID)
            ->first();

        $item = ItensPedido::where('item_pedido', $item_pedido)
            ->where('item_id', $item_id)
                ->first();

        if (Hash::check($request->password, $password->password)):
            if ($item_quantidade == $item->item_quantidade || !$item_quantidade):
                DB::table('itens_pedido')
                    ->where('item_pedido', $item_pedido)
                        ->where('item_id', $item_id)
                            ->update([
                                'item_delete'=> self::IS_CANCEL,
                                'item_quantidade' => $item->item_quantidade * (-1),
                                'item_total' => $item->item_total * 0
                            ]);
            else:
                DB::table('itens_pedido')
                    ->where([['item_pedido', $item_pedido], ['item_id', $item_id]])
                        ->update([
                            'item_quantidade' => $item->item_quantidade - $item_quantidade,
                            'item_total' => $item->item_total - ($item_quantidade * $item->item_price)
                        ]);

                $AddCanceledItem = new ItensPedido();
                $AddCanceledItem->item_pedido = $item_pedido;
                $AddCanceledItem->item_id = $item_id;
                $AddCanceledItem->item_quantidade = $item_quantidade * (-1);
                $AddCanceledItem->item_total = $item->item_total * 0;
                $AddCanceledItem->item_delete = true;
                $AddCanceledItem->item_price = $item->item_price;
                $AddCanceledItem->item_emissao = $item->item_emissao;
                $AddCanceledItem->item_option = $item->item_option;
                $AddCanceledItem->save();
            endif;
            event(new CancelOrder($item));
            return response()->json("Item cancelado com sucesso");
        endif;

        return response()->json("Senha invalida !");
    }

    public function EditOrderStat(Request $request, $item_pedido)
    {

        $request->validate([
            'password' => ['required']
        ]);
        
        $password = User::where('id', self::USER_ID)
            ->first();

        if ($request->status_id != 5 && $request->status_id != 6):
            if (Hash::check($request->password, $password->password)):
                DB::table('pedidos')
                    ->where('id', $item_pedido)
                        ->update([
                            'status_id' => $request->status_id
                        ]);
                return response()
                        ->json([
                            "msg" =>"Modo de pagamento editado com sucesso ! ",
                            "statut" => 200
                        ]);
            endif;
        endif;

        return response()
            ->json([
                    "msg"=>"This status is not allowed for this action !",
                    "statut" => 404
                ]);
    }

    public function getEmploye()
    {
        $employe = DB::table("users")
            ->select(
                "users.id",
                "users.name",
                "users.email",
                "users.tel",
                "users_group.groupe",
                "users_group.id as group_id"
            )
                ->join("users_group", "users.group_id", "=", "users_group.id")
                    ->where('isactive', true)
                        ->get();

        return response()->json($employe);
    }

    public function getToupdateEmploye($id)
    {
        return response()
            ->json(User::where('id', $id)->get());
    }

    public function ToDeleteEmploye($id)
    {
        DB::table('users')
            ->where('id', $id)
                ->update([
                    'isactive' => false
                ]);
        
        return response()
                ->json("Usuario deletado com sucesso");
    }

    public function updateEmployeStatus($id, $group_id)
    {
        DB::table("users")
            ->where("id", $id)
                ->update([
                    "group_id" => $group_id
                ]);

        return response()
            ->json("Hieraquia editada com sucesso");
    }

    public function EmployeUpdate(Request $request)
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
                ->json("Usuario editado com sucesso");
        }catch (Exception $e) {
            DB::rollBack();
            return response()->json("usuario não pode ser editado, tente novamente");
        }
    }
}
