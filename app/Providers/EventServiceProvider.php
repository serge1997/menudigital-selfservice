<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\CancelOrder;
use App\Events\StockReduced;
use App\Listeners\CancelOrderListener;
use App\Listeners\ReduceStockItem;
use App\Events\IsRuputureEvent;
use App\Listeners\ActiveRuputureListener;
use App\Events\RequisitionSended;
use App\Listeners\SendEmailRequistion;
use App\Listeners\DeliveryDevolutionEmail;
use App\Events\SendedDeliveryDevolutionEmail;
use App\Events\ClosingNotified;
use App\Listeners\SendClosingNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        CancelOrder::class => [
            CancelOrderListener::class
        ],
        StockReduced::class => [
            ReduceStockItem::class
        ],

        IsRuputureEvent::class => [
            ActiveRuputureListener::class
        ],

        RequisitionSended::class => [
            SendEmailRequistion::class
        ],

        SendedDeliveryDevolutionEmail::class => [
            DeliveryDevolutionEmail::class
        ],

        ClosingNotified::class => [
            SendClosingNotification::class
        ]

    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
