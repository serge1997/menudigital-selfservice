<?php
namespace App\Main\TableNumber;

use Illuminate\Database\Eloquent\Collection;

interface TableNumberRepositoryInterface
{
    public function getAllFreeTable();
    public function getAllBusyTable(): Collection;
}
