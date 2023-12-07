<?php

namespace App\Listeners;

use App\Events\CancelOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ItensPedido;
use Illuminate\Support\Facades\DB;

class CancelOrderListener
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
    public function handle(CancelOrder $event): void
    {
        $item = $event->item;
        $OrderId = $item->item_pedido;
        $itemId = $item->item_id;

        $OrderItems = ItensPedido::select('item_id')->where("item_pedido", $OrderId)->count();
        $itemDelete = ItensPedido::select('item_id')->where([["item_delete", 1], ['item_pedido', $OrderId]])->count();

        if ($OrderItems == $itemDelete):
                DB::table('pedidos')
                    ->where('id', $OrderId)
                        ->update([
                            'ped_delete' => 1,
                            'status_id' => 5
                        ]);
       endif;






    }
}
