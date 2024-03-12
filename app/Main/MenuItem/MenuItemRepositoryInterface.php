<?php
namespace App\Main\MenuItem;

use App\Models\Menuitems;
use Illuminate\Database\Eloquent\Collection;

interface MenuItemRepositoryInterface
{
    public function create($request);
    public function getAll(): Collection;
    public function find($id): Menuitems;
    public function beforeSave(string $item_name);
    public function delete($id);
    public function update($request);
    public function search($request): Collection;
    public function setRupture($id): String;
    public function expense($request): void; //despesa menu item
}
