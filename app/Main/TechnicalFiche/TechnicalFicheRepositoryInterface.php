<?php
namespace App\Main\TechnicalFiche;


use Illuminate\Database\Eloquent\Collection;


interface TechnicalFicheRepositoryInterface
{
    public function findByItemId($id): Collection;
    public function addNewItemToItemFiche($request): void;
    public function beforeSave($item_id, $product_id);
    public function deleteProductFromItemFiche($request, $item_id, $product_id): void;
    public function editProductQuantity($request): void;
}
