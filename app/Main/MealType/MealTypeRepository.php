<?php
namespace App\Main\MealType;

use App\Http\Services\UserInstance;
use App\Models\MealType;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;
class MealTypeRepository implements MealTypeRepositoryInterface
{

    /**
     * @throws Exception
     */
    public function create($request)
    {
        $type = new MealType();
        $type->desc_type = $request->desc_type;
        $this->beforeSave($type->desc_type);
        if($request->hasFile('foto_type') && $request->file('foto_type')->isValid()){
            $foto = $request->foto_type;
            $extension = $foto->extension();
            $fotoname = md5($foto->getClientOriginalName(). strtotime("now")). ".". $extension;
            $foto->move(public_path('img/type'), $fotoname);
            $type->foto_type = $fotoname;
        }
        $auth = $request->session()->get('auth-vue');
        foreach (UserInstance::get_user_roles($auth) as $menu):
            if ($menu->role_id === Role::MANAGER):
                $type->save();
                return true;
            endif;
        endforeach;
        throw new Exception("You dont have permission");

    }

    /**
     * @throws Exception
     */
    public function beforeSave($type_name): void
    {
        $type = MealType::where('desc_type', $type_name)->exists();
        if ($type)
        {
            throw new Exception("Tipo de item de menu já registrado");
        }
    }

    public function getAll()
    {
        return MealType::all();
    }

    public function getMealtypeByMenuItem()
    {
        $type = DB::table("menu_mealtype")
        ->select(
                "menu_mealtype.id_type",
                "menu_mealtype.desc_type",
                "menu_mealtype.foto_type",
                DB::raw("COUNT(menuitems.id) as item_qty")
            )
            ->join('menuitems', 'menu_mealtype.id_type', '=', 'menuitems.type_id')
                ->where('menuitems.item_status', '=', true)
                    ->groupby(
                        "menu_mealtype.id_type",
                        "menu_mealtype.desc_type",
                        "menu_mealtype.foto_type"
                    )
                        ->get();

        return $type;
    }

    public function getMenuItemsByMealType($id)
    {
        $itemsOfType = DB::table("menuitems")->select('*')
            ->join("menu_mealtype", "menuitems.type_id", "=", "menu_mealtype.id_type")
                ->where("menuitems.type_id", $id)
                    ->get();

        return $itemsOfType;

    }
}
