<?php
namespace App\Http\Services\Stock;

use App\Http\Services\Stock\StockServiceInterFace;
use Illuminate\Support\Facades\DB;
use App\Models\Menuitems;
use App\Models\Technicalfiche;
use App\Models\Saldo;
use App\Models\Product;
use Illuminate\Support\Arr;
class StockServiceRepository implements StockServiceInterFace
{
    public $check_is_rupture_saldo;
    public $check_lowtsock_min_quantity;

    public function MountSheetArray(string $id): array
    {
        $sheet_values = [];
        $sheet = Technicalfiche::where('itemID', $id)->get();
        foreach ($sheet as $item)
        {
            $sheet_values["itemID"] = $item->itemID;
            $sheet_values["productID"][] = $item->productID;
            $sheet_values["quantity"][] = $item->quantity;
        }
        return $sheet_values;
    }
    public function ControleItemLowStockRupured(array $item_ids)
    {
        // TODO: Implement ControleItemLowStockRupured() method.
        foreach ($item_ids as $key => $item_id) {
            $fiche = DB::table('technicalfiches')
                ->select('itemID', 'productID')
                ->where('itemID', $item_id)
                ->get();

            $sheet_values = $this->MountSheetArray($item_id);
            if (isset($sheet_values["productID"])):
                for ($i = 0; $i <= count($sheet_values["productID"]); $i++):
                    $actual_product_inventory = DB::table('saldos')
                        ->select('saldoFinal', 'productID')
                            ->where('productID', head($sheet_values["productID"]))
                                ->first();

                    $product = DB::table('products')
                        ->where('id', head($sheet_values["productID"]))
                            ->first();

                    if ($product->prod_unmed == "bt"):
                        if ($actual_product_inventory->saldoFinal < $product->min_quantity && $actual_product_inventory->saldoFinal > 1):
                            DB::table('menuitems')
                                ->where('id', $item_id)
                                ->update([
                                    'is_lowstock' => true,
                                ]);
                            break;
                        else:
                            if ($actual_product_inventory->saldoFinal <= 0):
                                DB::table('menuitems')
                                    ->where('id', $item_id)
                                    ->update([
                                        'item_rupture' => true,
                                        'is_lowstock' => false
                                    ]);
                             break;
                            endif;
                        endif;
                    else:
                        $divide_quantity = $actual_product_inventory->saldoFinal / $product->prod_contain;
                        if ($divide_quantity < $product->min_quantity && $actual_product_inventory->saldoFinal > 1):
                            DB::table('menuitems')
                                ->where('id', $item_id)
                                ->update([
                                    'is_lowstock' => true,
                                ]);
                            break;
                        else:
                            if ($divide_quantity <= 0):
                                DB::table('menuitems')
                                    ->where('id', $item_id)
                                    ->update([
                                        'item_rupture' => true,
                                        'is_lowstock' => false
                                    ]);
                                break;
                            endif;
                        endif;
                    endif;
                    array_shift($sheet_values["productID"]);
                endfor;
            endif;
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
                $date = $old_saldo->emissao ?? $hoje;
                if ($date == $hoje):
                    DB::table('saldos')
                        ->where('productID', $item->productID)
                        ->update([
                            'saldoFinal' => $old_saldo->saldoFinal - ($item->quantity * $product_quantitys[$key]),
                        ]);
                else:
                    DB::table('saldos')
                        ->where('productID', $item->productID)
                        ->update([
                            'emissao'      => $hoje,
                            'saldoInicial' => $old_saldo->saldoFinal,
                            'saldoFinal'   => $old_saldo->saldoFinal - ($item->quantity * $product_quantitys[$key]),
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
            $this->check_is_rupture_saldo = [];
            $take_sheet = Technicalfiche::where('itemID', $itemID->itemID)->get();
            foreach ($take_sheet as $sheet)
            {
                $product_min = Product::where('id', $sheet->productID)->first();
                $takeItemMenuProductInventory = Saldo::where('productID', $sheet->productID)->first();
                $this->check_is_rupture_saldo[] = $takeItemMenuProductInventory->saldoFinal ?? 0;
                $saldoFinal = $takeItemMenuProductInventory->saldoFinal ?? $product_min->prod_contain;
                if ($product_min->prod_unmed == "bt"):
                    if ($product_min->min_quantity <= $takeItemMenuProductInventory->saldoFinal && $takeItemMenuProductInventory->saldoFinal > 0):
                        DB::table('menuitems')
                            ->where('id', $itemID->itemID)
                            ->update([
                                'is_lowstock' => false
                            ]);
                    endif;
                else:
                    if ($product_min->min_quantity <= ((int)$saldoFinal  / $product_min->prod_contain)):
                        DB::table('menuitems')
                            ->where('id', $itemID->itemID)
                            ->update([
                                'is_lowstock' => false
                            ]);
                    endif;
                endif;
            }
            if (!in_array(0, $this->check_is_rupture_saldo)):
                DB::table('menuitems')
                    ->where('id', $itemID->itemID)
                    ->update([
                        'item_rupture' => false
                    ]);
            endif;

        }

    }
}
