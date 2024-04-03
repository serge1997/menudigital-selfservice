<?php
namespace App\Http\Services\Menuitem;

use App\Models\Menuitems;
use App\Models\MealType;
use Exception;
class MenuItemRepository implements MenuItemInterface
{
    /**
     * @param string $item_name
     * @return void
     * @throws Exception
     *@see Menuitems;
     */
    public function beforeSave(string $item_name): void
    {
        $item_name = Menuitems::where('item_name', $item_name)->exists();
        if ($item_name)
        {
            throw new Exception("O item já existe");
        }
    }

    /**
     * @param $type_name
     * @return void
     * @throws Exception
     * @see MealType;
     */
    public function beforeSaveMealType($type_name): void
    {
        $type = MealType::where('desc_type', $type_name)->exists();
        if ($type)
        {
            throw new Exception("Tipo de item de menu já registrado");
        }
    }
}
