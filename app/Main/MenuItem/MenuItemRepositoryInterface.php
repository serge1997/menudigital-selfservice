<?php
namespace App\Main\MenuItem;

interface MenuItemRepositoryInterface
{
    public function create($request);
    public function getAll();
    public function find($id);
    public function beforeSave(string $item_name);
}
