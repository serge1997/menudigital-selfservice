<?php

namespace App\Listeners;

use App\Events\ClosingNotified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Mail;
use App\Models\User;
use App\Models\Restaurant;
use DateTime;

class SendClosingNotification
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
    public function handle(ClosingNotified $event): void
    {
        $pedido = $event->pedido;
        $restuarant = Restaurant::find(1)->first();
        $latestOrder = $pedido->latest()->first();
        $latestOrderDate = date('Y-m-d', strtotime("{$latestOrder->created_at}"));
        $today = new DateTime();
        $today = $today->format('Y-m-d');
        $open = "";
        $latestTime = substr($latestOrder->created_at, 11, 2);
        if (in_array($latestTime, ['00', '01', '02', '03', '04', '05', '06'])){
            $open .= date('Y-m-d', strtotime("{$latestOrderDate} - 1 day")). $restuarant->res_open;
        }else{
            $open .= $today .' '. $restuarant->res_open;
        }
        $manager = User::find(User::GERENTE);
        $order = $pedido::select(
            'st.stat_desc',
            DB::raw('SUM(it.item_total) AS venda')
        )
            ->join('itens_pedido AS it', 'pedidos.id', '=', 'it.item_pedido')
                ->join('status as st', 'pedidos.status_id', 'st.id')
                    ->where([
                        ['st.id', '<>', '5'],
                        ['pedidos.ped_delete', 0],
                        ['it.item_delete', 0],
                    ])
                        ->whereBetween('pedidos.created_at' , [$open, $latestOrder->created_at])
                            ->groupBy('st.stat_desc')
                                ->get();
        Mail::send('Mail.closingNotification', ['data' => $order, 'restaurant' => $restuarant], function($header) use($manager) {
            $header->to($manager->email);
            $header->subject('Notificação de fechamento');
        });
    }
}
