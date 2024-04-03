<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PurchaseRequisition;
use App\Models\StockEntry;
use App\Models\User;

class DevolutionDevoliverySended extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected PurchaseRequisition $delivery
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
            subject: 'Devolution Devolivery Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'Mail.devolutionDeliverySended',
            with: [
                'delivered_at' => StockEntry::where([['is_delete', false], ['requisition_id', $this->delivery->id]])->first()->emissao,
                'author' => User::find(session('auth-vue'))->first()->name,
                'requisitionCode' => PurchaseRequisition::find($this->delivery->id)->requisition_code
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
