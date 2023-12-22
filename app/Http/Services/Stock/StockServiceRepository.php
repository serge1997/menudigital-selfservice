<?php
namespace App\Http\Services\Stock;

use App\Http\Services\Stock\StockServiceInterFace;
use Illuminate\Support\Facades\DB;
use App\Models\Menuitems;
use App\Models\Technicalfiche;
use App\Models\Saldo;
use App\Models\Product;
class StockServiceRepository implements StockServiceInterFace
{
    public $check_is_rupture_saldo;
    public $check_lowtsock_min_quantity;
    public function ControleItemLowStockRupured(array $item_ids)
    {
        // TODO: Implement ControleItemLowStockRupured() method.
        foreach ($item_ids as $item_id)
        {
            $fiche = DB::table('technicalfiches')
                ->select('itemID', 'productID')
                ->where('itemID', $item_id)
                ->get();

            foreach ($fiche as $saldo):

                //pegar o saldo atual do produto
                $actual_product_inventory = DB::table('saldos')
                    ->select('saldoFinal', 'productID')
                    ->where('productID', $saldo->productID)
                    ->first();
                //pegar as informação do produto
                $product = DB::table('products')
                    ->where('id', $saldo->productID)
                    ->first();
                //verificar a quantidade se for abaixo do saldo minimo
                if ($product->prod_unmed == "bt"):
                    if ((int)$actual_product_inventory->saldoFinal < (int)$product->min_quantity && (int)$actual_product_inventory->saldoFinal > 0):
                        DB::table('menuitems')
                            ->where('id', $item_id)
                            ->update([
                                'is_lowstock' => true,
                            ]);
                    else:
                        DB::table('menuitems')
                            ->where('id', $item_id)
                                ->update([
                                    'item_rupture' => true,
                                    'is_lowstock'  => false
                                ]);
                    endif;
                else:
                    $divide_quantity = $actual_product_inventory->saldoFinal / $product->prod_contain;
                    if ($divide_quantity < (int)$product->min_quantity && (int)$actual_product_inventory->saldoFinal > 0):
                        DB::table('menuitems')
                            ->where('id', $item_id)
                            ->update([
                                'is_lowstock' => true,
                            ]);
                    else:
                        DB::table('menuitems')
                            ->where('id', $item_id)
                                ->update([
                                    'item_rupture' => true,
                                    'is_lowstock'  => false
                                ]);
                    endif;
                endif;
            endforeach;
        }

    }

    public function StockOutProduct(array $item_ids, array $product_quantitys)
    {
        $hoje = new \DateTime();
        $hoje = $hoje->format("Y-m-d");
        foreach ($item_ids as $key => $itemID)
        {
            $item_fiche = Technicalfiche::where('itemID', $itemID)->get();

            foreach ($item_fiche as $item):
                $old_saldo = DB::table('saldos')
                    ->select(DB::raw('CAST(saldoFinal AS DECIMAL(6, 2)) AS saldoFinal'), 'emissao')
                    ->where('productID', $item->productID)->first();
                if ($old_saldo->emissao == $hoje):
                    DB::table('saldos')
                        ->where('productID', $item->productID)
                        ->update([
                            'saldoFinal' => $old_saldo->saldoFinal - ($item->quantity * $product_quantitys[$key]),
                        ]);
                else:
                    DB::table('saldos')
                        ->where('productID', $item->productID)
                        ->update([
                            'emissao' => $hoje,
                            'saldoInicial' => $old_saldo->saldoFinal,
                            'saldoFinal' => $old_saldo->saldoFinal - ($item->quantity * $product_quantitys[$key]),
                        ]);
                endif;
            endforeach;
        }
    }
    public function CheckRuptureLowStockState(string $productID)
    {
        $fiche = Technicalfiche::where('productID', $productID)->get();
        foreach ($fiche as $itemID)
        {
            $take_sheet = Technicalfiche::where('itemID', $itemID->itemID)->get();
            foreach ($take_sheet as $sheet)
            {
                $product_min = Product::where('id', $sheet->productID)->first();
                $takeItemMenuProductInventory = Saldo::where('productID', $sheet->productID)->first();
                $this->check_is_rupture_saldo[] = $takeItemMenuProductInventory->saldoFinal ?? 0;
                $saldoFinal = $takeItemMenuProductInventory->saldoFinal ?? $product_min->prod_contain;
                if ($product_min->min_quantity < ($saldoFinal  / $product_min->prod_contain)){
                    DB::table('menuitems')
                        ->where('id', $itemID->itemID)
                        ->update([
                            'is_lowstock' => false
                        ]);
                }
            }

            if (in_array(0, $this->check_is_rupture_saldo)){
                return false;
            }else {
                DB::table('menuitems')
                    ->where('id', $itemID->itemID)
                        ->update([
                            'item_rupture' => false
                        ]);
            }

        }

    }
}
