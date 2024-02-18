<?php
namespace App\Main\OrderStatus;

use Illuminate\Database\Eloquent\Collection;
use App\Models\OrderStatus;

class OrderStatusRepository implements OrderStatusRepositoryInterface
{
    public function getAll(): Collection
    {
        return new Collection(OrderStatus::whereNotIn('id', [5, 6])->get());
    }
}
