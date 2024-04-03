<?php

namespace App\Listeners;

use App\Events\IsRuputureEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class ActiveRuputureListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(IsRuputureEvent $event): void
    {
        $item = $event->item;

        //Pegar todos os produto que compoe o item do menu
        $fiche = DB::table('technicalfiches')
            ->select('itemID', 'productID')
                ->where('itemID', $item->item_id)
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
            if ($product->prod_unmed === "bt"):
                if ($actual_product_inventory->saldoFinal <= $product->min_quantity && $actual_product_inventory->saldoFinal > 0):
                    DB::table('menuitems')
                        ->where('id', $item->item_id)
                            ->update([
                                'is_lowstock' => true,
                            ]);
                else:
                    if ($actual_product_inventory->saldoFinal <= 0):
                        DB::table('menuitems')
                            ->where('id', $item->item_id)
                            ->update([
                                'item_rupture' => true,
                                'is_lowstock'  => false
                            ]);
                    endif;
                endif;
            else:
                $divide_quantity = $actual_product_inventory->saldoFinal / $product->prod_contain;
                if ($divide_quantity <= $product->min_quantity && $actual_product_inventory->saldoFinal > 0):
                    DB::table('menuitems')
                        ->where('id', $item->item_id)
                        ->update([
                            'is_lowstock' => true,
                        ]);
                else:
                    if ($divide_quantity <= 0):
                        DB::table('menuitems')
                            ->where('id', $item->item_id)
                            ->update([
                                'item_rupture' => true,
                                'is_lowstock'  => false
                            ]);
                    endif;
                endif;
            endif;
        endforeach;
    }
}
