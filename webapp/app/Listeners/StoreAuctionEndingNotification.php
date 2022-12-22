<?php

namespace App\Listeners;

use App\Events\AuctionEnding;
use App\Http\Controllers\AuctionEndingNotificationController;

class StoreAuctionEndingNotification
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
     * @param  \App\Events\AuctionEnding  $event
     * @return void
     */
    public function handle(AuctionEnding $event)
    {
        AuctionEndingNotificationController::createAuctionEndingNotification($event->notification_date,$event->auction->id, $event->users);
    }
}
