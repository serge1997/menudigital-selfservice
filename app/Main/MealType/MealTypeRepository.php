<?php
namespace App\Main\MealType;

use App\Http\Services\UserInstance;
use App\Models\MealType;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
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
}
