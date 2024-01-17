<?php
namespace App\Http\Services\Stock;

use App\Http\Services\Stock\StockServiceInterFace;
use Illuminate\Support\Facades\DB;
use App\Models\Menuitems;
use App\Models\Technicalfiche;
use App\Models\Saldo;
use App\Models\Product;
use App\Models\Cart;
use Exception;
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
            $sheet_values["itemID"]      = $item->itemID;
            $sheet_values["productID"][] = $item->productID;
            $sheet_values["quantity"][]  = $item->quantity;
        }
        return $sheet_values;
    }
    public function ControleItemLowStockRuptured(array $item_ids): void
    {
        foreach ($item_ids as $item_id) {
            $sheet_values = $this->MountSheetArray($item_id);
            if (count($sheet_values["productID"]) > 1):
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
                                        'is_lowstock'  => false
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
                                        'is_lowstock'  => false
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

    /**
     * @throws Exception
     */
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
                $itemProductInFiche = Technicalfiche::where([['itemID', $itemID], ['productID', $item->productID]])->first();
                $date = $old_saldo->emissao ?? $hoje;
                //var_dump($item_ids);die;
                $beforeQuantity = $old_saldo->saldoFinal - ($product_quantitys[$key] * $itemProductInFiche->quantity);
                //var_dump($beforeQuantity); die;
                if ($old_saldo->saldoFinal < ($product_quantitys[$key] * $itemProductInFiche->quantity) || $beforeQuantity < 0 ){
                    throw new Exception("Quantidade em estoque insuficiante ");
                }
                if ($date == $hoje):
                    DB::table('saldos')
                        ->where('productID', $item->productID)
                        ->update([
                            'saldoFinal' => $old_saldo->saldoFinal - ($product_quantitys[$key] * $itemProductInFiche->quantity),
                        ]);
                else:
                    DB::table('saldos')
                        ->where('productID', $item->productID)
                        ->update([
                            'emissao'      => $hoje,
                            'saldoInicial' => $old_saldo->saldoFinal,
                            'saldoFinal'   => $old_saldo->saldoFinal - ($product_quantitys[$key] * $itemProductInFiche->quantity),
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

    /**
     * @throws \Exception
     */
    public static function SetItemSaldoZeroException(string $tableNumber = null, string $menuitem = null): void
    {
        if (is_null($menuitem) && !is_null($tableNumber)) {
            $cart = Cart::where('tableNumber', $tableNumber)->get();
            foreach ($cart as $item) {
                $item_sheets = Technicalfiche::where("itemID", $item->item_id)->get();
                if (count($item_sheets) < 1) {
                    Cart::where('tableNumber', $tableNumber)
                        ->delete();
                    throw new Exception("Ação não pode ser concluída. O item não tem ficha tecnica");
                }
                foreach ($item_sheets as $sheet) {
                    $productID = $sheet->productID ?? 19;
                    $saldo = Saldo::where('productID', $productID)->first();
                    $inventoty = $saldo->saldoFinal ?? 0;

                    if (!$inventoty || $inventoty <= 0) {
                        Cart::where('tableNumber', $tableNumber)->delete();
                        throw new Exception("Ação não pode ser concluida. O pedido contem item com estoque zerado. {$productID}");
                    }
                }
            }
        }else {
            $item_sheets = Technicalfiche::where("itemID", $menuitem)->exists();
            if (!$item_sheets) {
                throw new Exception("Ação não pode ser concluída. O item não tem ficha tecnica");
            }
        }
    }

    /**
     * @throws Exception
     */
    public static function checkSetItemSaldoZeroAddItemToOrder(int $itemID)
    {
        $item_sheets = Technicalfiche::where("itemID",$itemID)->get();
        foreach ($item_sheets as $sheet)
        {
            $productID = $sheet->productID ?? 19;
            $saldo = Saldo::where('productID', $productID)->first();
            $inventoty = $saldo->saldoFinal ?? 0;

            if (!$inventoty|| $inventoty <= 0){
                throw new Exception("Ação não pode ser concluida. O item está com estoque zerado. {$productID}");
            }
        }
    }

    public function checkAllwaysRupture()
    {
        $menuitem = Menuitems::all();

        foreach ($menuitem as $item){
            $sheet = Technicalfiche::where("itemID", $item->id)->get();

            foreach ($sheet as $saldo)
            {
                $checksaldo = Saldo::where("productID", $saldo->productID)->first();
                $product = Product::where('id', $saldo->productID)->first();
                $inventory = $checksaldo->saldoFinal ?? 0;
                if ($inventory  <= 0 || !$checksaldo)
                {
                    DB::table("menuitems")
                        ->where("id", $item->id)
                            ->update([
                                "is_lowstock"  => false,
                                "item_rupture" => true
                            ]);
                }else {
                    if ($inventory < $product->min_quantity){
                        DB::table("menuitems")
                            ->where("id", $item->id)
                            ->update([
                                "is_lowstock"  => true,
                            ]);
                    }
                }
            }
        }
    }
}
