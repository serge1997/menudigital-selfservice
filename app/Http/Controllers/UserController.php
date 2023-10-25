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

        $user = User::where('email', $request->email)->first();

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
}
