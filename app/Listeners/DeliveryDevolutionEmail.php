<?php

namespace App\Listeners;

use App\Events\SendedDeliveryDevolutionEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Models\StockEntry;
use Mail;
use Illuminate\Http\Request;

class DeliveryDevolutionEmail
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
    public function handle(SendedDeliveryDevolutionEmail $event): void
    {
        $request = new Request();
        $productData = [];
        $requisition = $event->requisition;
        $product = StockEntry::where('requisition_id', $requisition->id)->get();
        foreach($product as $p){
           $productData[] = $p->productID;
        }
        $manager = User::find(User::GERENTE);
        $id = session('auth-vue');
        $user = User::find($id);
        $stock = StockEntry::where([
            ['requisition_id', $requisition->id],
        ])->whereIn('productID', $productData)->sum('totalCost');

        Mail::send('Mail.devolutionDelivery', ['user' => $user, 'requisition' => $requisition, 'total' => $stock], function($header) use ($manager) {
            $header->to($manager->email);
            $header->subject('Devolução de entrega');
        });

    }
}
