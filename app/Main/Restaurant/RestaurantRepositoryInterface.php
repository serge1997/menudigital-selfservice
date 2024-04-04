<?php
namespace App\Main\Restaurant;

use Illuminate\Database\Eloquent\Collection;

interface RestaurantRepositoryInterface
{
    public function create($request);
    public function find(): Collection;
}
