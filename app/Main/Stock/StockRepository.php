<?php
namespace App\Main\Stock;

use App\Models\StockEntry;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class StockRepository implements StockRepositoryInterface
{
    public function findLastProductEntry($id)
    {
        $productInfo = StockEntry::where('productID', $id)
            ->orderBy('emissao', 'DESC')
                ->first();
        if (!isset($productInfo->productID)){
            throw new Exception("Produto não tem custo. Custo necessario para processar a ficha tecnica");
        }

        return $productInfo;
    }
}
