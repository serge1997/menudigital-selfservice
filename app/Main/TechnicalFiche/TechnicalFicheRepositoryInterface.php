<?php
namespace App\Main\TechnicalFiche;


use Illuminate\Database\Eloquent\Collection;


interface TechnicalFicheRepositoryInterface
{
    public function create($request);
    public function show(string $id): Collection;
    public function findByItemId($id): Collection;
    public function addNewItemToItemFiche($request): void;
    public function beforeSaveItem($item_id);
    public function beforeSave($item_id, $product_id);
    public function deleteProductFromItemFiche($request, $item_id, $product_id): void;
    public function editProductQuantity($request): void;
    public function findFicheByItemId($id): Collection;
    public function itemHasOneProduct($item_id):bool;
    public function inactiveMenuItemFiche($item_id): void;
}
