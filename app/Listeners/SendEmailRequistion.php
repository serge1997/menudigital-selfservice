<?php

namespace App\Listeners;

use App\Events\RequisitionSended;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use DateTime;
use Mail;

class SendEmailRequistion
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
    public function handle(RequisitionSended $event): void
    {
        $requisition = $event->requisition;
        $requisition->delivery_date = new DateTime($requisition->delivery_date);
        $requisition->delivery_date = $requisition->delivery_date->format('d/m/Y');
        $requerente = User::find($requisition->user_id);
        $manager = User::find(User::GERENTE);
        Mail::send('Mail.requisition', ['user' => $requerente, 'requisition' => $requisition], function($header) use ($manager) {
            $header->to($manager->email);
            $header->subject('Requisition de compra');
        });
    }
}
