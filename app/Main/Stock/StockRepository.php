<?php
namespace App\Main\Stock;

use App\Models\StockEntry;
use Illuminate\Database\Eloquent\Collection;

class StockRepository implements StockRepositoryInterface
{
    public function findLastProductEntry($id)
    {
        $productInfo = StockEntry::where('productID', $id)
            ->orderBy('emissao', 'DESC')
                ->first();

        return $productInfo;
    }
}
