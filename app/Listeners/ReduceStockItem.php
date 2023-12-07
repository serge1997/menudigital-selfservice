<?php

namespace App\Listeners;

use App\Events\StockReduced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ItensPedido;
use App\Models\Technicalfiche;
use Illuminate\Support\Facades\DB;
use App\Models\Saldo;
use DateTime;
use PhpParser\Node\Stmt\Foreach_;

class ReduceStockItem
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
    public function handle(StockReduced $event): void
    {
        $itemmenu = $event->item;
        $hoje = new DateTime();
        $hoje = $hoje->format('Y-m-d');
        $itemId = $itemmenu->item_id;
        $item_fiche = Technicalfiche::where('itemID', $itemId)->get();
        foreach ($item_fiche as $item):
            $old_saldo = DB::table('saldos')->where('productID', $item->productID)->first();
            if ($old_saldo->emissao == $hoje):
                DB::table('saldos')
                ->where('productID', $item->productID)
                    ->update([
                        'saldoFinal' => $old_saldo->saldoFinal - ($item->quantity * $itemmenu->item_quantidade),
                    ]);
            else:
                DB::table('saldos')
                    ->where('productID', $item->productID)
                    ->update([
                        'emissao' => $hoje,
                        'saldoInicial' => $old_saldo->saldoFinal,
                        'saldoFinal' => $old_saldo->saldoFinal - ($item->quantity * $itemmenu->item_quantidade),
                    ]);
            endif;
        endforeach;

    }
}
