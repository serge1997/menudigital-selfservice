<?php
namespace App\Main\Position;

use Illuminate\Support\Collection;
use App\Models\Position;

class PositionRepository implements PositionRepositoryInterface
{
    public function getAll(): Collection
    {
        return new Collection(
            Position::all()
        );
    }
}
