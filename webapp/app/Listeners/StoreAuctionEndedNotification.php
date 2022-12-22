<?php

namespace App\Listeners;

use App\Events\AuctionEnded;
use App\Http\Controllers\AuctionEndedNotificationController;

class StoreAuctionEndedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AuctionEnded  $event
     * @return void
     */
    public function handle(AuctionEnded $event)
    {
        AuctionEndedNotificationController::createAuctionEndedNotification($event->notification_date,$event->auction->id, $event->users);
    }
}
