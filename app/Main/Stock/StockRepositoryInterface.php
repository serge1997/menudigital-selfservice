<?php
namespace App\Main\Stock;

use Illuminate\Database\Eloquent\Collection;

interface StockRepositoryInterface
{
    public function findLastProductEntry($id);
    public function filterDataTable($request): Collection;
}
