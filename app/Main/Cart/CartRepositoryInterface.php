<?php
namespace App\Main\Cart;


interface CartRepositoryInterface
{
    public function getCartItens($table): array;
    public function addToCart($id, $request);
    public function addQuantity($id): array;
    public function reduceQuantity($id): array;
    public function deleteFromCart($cartId, $table);
}
