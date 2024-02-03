<?php
namespace App\Main\Position;

use Illuminate\Support\Collection;

interface PositionRepositoryInterface
{
    public function getAll(): Collection;
    public function updateByUser($user_id, $position_id, $request): void;
}
