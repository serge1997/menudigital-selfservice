<?php
namespace App\Main\MenuItem;

use App\Models\Menuitems;
use App\Http\Services\UserInstance;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use Exception;

class MenuItemRepository implements MenuItemRepositoryInterface
{

    public function create($request)
    {

        $user_id = $request->session()->get('auth-vue');
        $values = $request->all();
        try{
            $this->beforeSave($request->item_name);
            foreach (UserInstance::get_user_roles($user_id) as $item_role):
                if ($item_role->role_id === Role::MANAGER):
                    $item = new Menuitems($values);
                    $item -> save();
                    return response()->json("Item Salvou com sucesso", 200);
                 endif;
            endforeach;
            return response()->json("User don't have permission", 400);
        }catch(\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(),400);
        }
    }

    public function getAll()
    {
        return MenuItems::all();
    }

    public function find($id)
    {
        return Menuitems::find($id);
    }

    public function beforeSave(string $item_name)
    {
        $item_name = Menuitems::where('item_name', $item_name)->exists();
        if ($item_name)
        {
            throw new Exception("O item já existe");
        }
    }
}
