<?php
namespace App\Main\Stock;

use App\Models\StockEntry;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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

    public function filterDataTable($request): Collection
    {
        $query = "
            SELECT
                MAX(st.emissao) emissao,
                max(st.unitCost) unitCost,
                st.productID,
                p.prod_name,
                p.min_quantity,
                sp.sup_name,
                CASE
                    WHEN p.prod_unmed = 'bt' THEN TRUNCATE(sa.saldoFinal, 2)
                ELSE
                    TRUNCATE(sa.saldoFinal / p.prod_contain, 2)
                END saldoFinal,
                p.prod_unmed
            FROM stock_entries st
                INNER JOIN saldos sa
                    ON sa.productID = st.productID
                INNER join products p
                    ON p.id = st.productID
                    AND p.is_delete = 0
                INNER JOIN suppliers sp
                    ON sp.id = st.supplierID
            WHERE p.prod_name LIKE '%{$request->search_param}%' OR sp.sup_name LIKE '%{$request->search_param}%'
            GROUP BY
                st.productID,
                p.prod_name,
                sp.sup_name,
                p.prod_unmed,
                p.prod_contain,
                p.min_quantity,
                saldoFinal
            HAVING MAX(st.emissao)
            ORDER BY p.prod_name
        ";
        return new Collection(
            DB::select($query)
        );
    }
}
