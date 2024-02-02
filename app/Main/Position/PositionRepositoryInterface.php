<?php
namespace App\Main\Position;

use Illuminate\Support\Collection;

interface PositionRepositoryInterface
{
    public function getAll(): Collection;
}
