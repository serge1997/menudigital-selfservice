<?php
namespace App\Http\Services\Menuitem;

interface MenuItemInterface
{
    public function beforeSave(string $item_name): void;

    //meal type beforeSave
    public function beforeSaveMealType($type_name): void;
}
