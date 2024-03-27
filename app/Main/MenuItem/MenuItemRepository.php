<?php
namespace App\Main\MenuItem;

use App\Models\Menuitems;
use App\Http\Services\UserInstance;
use App\Http\Services\Util\Util;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Saldo;
use App\Models\Technicalfiche;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Permission;
use App\Models\Expense;
use App\Traits\AuthSession;

class MenuItemRepository implements MenuItemRepositoryInterface
{
    use Permission, AuthSession { AuthSession::autth insteadof Permission; }

    public function create($request)
    {

        $user_id = $request->session()->get('auth-vue');
        $values = $request->all();

        $this->beforeSave($request->item_name);
        foreach (UserInstance::get_user_roles($user_id) as $item_role):
            if ($item_role->role_id === Role::MANAGER):
                $item = new Menuitems($values);
                $item -> save();
                return ;
            endif;
        endforeach;
        throw new Exception(__('messages.permission'));

    }

    public function getAll(): Collection
    {
        $items = DB::table("menuitems")->select('*')
            ->join("menu_mealtype", "menuitems.type_id", "=", "menu_mealtype.id_type")
                ->where('menuitems.item_status', '=', true)
                    ->orderBy('menuitems.item_name')
                        ->get();

        return new Collection($items);
    }

    public function find($id): Menuitems
    {
        return Menuitems::find($id);
    }

    public function beforeSave(string $item_name)
    {
        $item_name = Menuitems::where('item_name', $item_name)->exists();
        if ($item_name)
        {
            throw new Exception(__('messages.beforesave_exception', ['model' => 'item']));
        }
    }

    public function setRupture($id): String
    {
       $item = $this->find($id);
       $message = $item->item_rupture ? "item habilitado" : "Item desabilitado";
       Menuitems::where('id', $id)
            ->update([
                'item_rupture' => !$item->item_rupture
            ]);

        return $message;
    }

    public function search($request): Collection
    {
        $items = Menuitems::select('*')
            ->join("menu_mealtype", "menuitems.type_id", "=", "menu_mealtype.id_type")
                ->where([['menuitems.item_name', 'like', '%'.$request->search.'%'], ['item_status', true]])
                    ->get();

        return new Collection($items);
    }

    public function delete($id)
    {
        Menuitems::where('id', $id)
            ->update([
                'item_status' => false
            ]);
    }

    public function update($request)
    {
        $item_name  = $request->item_name;
        $item_price = $request->item_price;
        $item_id    = $request->item_id;
        $item_desc  = $request->item_desc;

        Menuitems::where("id", $item_id)
            ->update([
                "item_name"  => $item_name,
                "item_desc"  => $item_desc,
                "item_price" => $item_price
            ]);
    }
}
