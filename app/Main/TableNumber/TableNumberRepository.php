<?php
namespace App\Main\TableNumber;

use App\Models\TableNumber;

class TableNumberRepository implements TableNumberRepositoryInterface
{
    public function getAll()
    {
        return TableNumber::all();
    }
}
