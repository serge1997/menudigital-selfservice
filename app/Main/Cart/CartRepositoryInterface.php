<?php
namespace App\Main\Cart;
use Illuminate\Support\Collection;


interface CartRepositoryInterface
{
    public function getCartItens($table): array;
    public function addToCart($id, $request);
    public function addQuantity($id): array;
    public function reduceQuantity($id): array;
    public function deleteFromCart($cartId);
}
