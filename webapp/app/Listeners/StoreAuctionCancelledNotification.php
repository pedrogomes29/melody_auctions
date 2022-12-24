<?php

namespace App\Listeners;

use App\Events\AuctionCancelled;
use App\Http\Controllers\AuctionsCancelledNotificationController;

class StoreAuctionCancelledNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AuctionCancelled  $event
     * @return void
     */
    public function handle(AuctionCancelled $event)
    {
        AuctionsCancelledNotificationController::createAuctionCancelledNotification($event->notification_date,$event->auction->id, $event->users);
    }
}
