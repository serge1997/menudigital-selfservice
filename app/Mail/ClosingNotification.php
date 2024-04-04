<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Pedido;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;

class ClosingNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
    )
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Closing Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $restuarant = Restaurant::find(1)->first();
        $latestOrder = Pedido::latest()->first();
        $latestOrderDate = date('Y-m-d', strtotime("{$latestOrder->created_at}"));
        $today = new \DateTime();
        $today = $today->format('Y-m-d');
        $open = "";
        $latestTime = substr("$latestOrder->created_at", 11, 2);
        if (in_array("$latestTime", ['00', '01', '02', '03', '04', '05', '06'])){
            $open .= date('Y-m-d', strtotime("{$latestOrder->created_at} - 1 day"));
        }else{
            $open .= $today .' '. $restuarant->res_open;
        }
        $orderDay = Pedido::select(
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
        return new Content(
            markdown: 'Mail.closingNotification',
            with: [
                'order' => $orderDay,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
