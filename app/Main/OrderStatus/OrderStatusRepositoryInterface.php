<?php
namespace App\Main\OrderStatus;

use Illuminate\Database\Eloquent\Collection;

interface OrderStatusRepositoryInterface
{
    public function getAll(): Collection;
}
