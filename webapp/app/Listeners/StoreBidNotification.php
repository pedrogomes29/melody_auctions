<?php

namespace App\Listeners;

use App\Events\NewBid;
use App\Http\Controllers\BidsNotificationController;

class StoreBidNotification
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
     * @param  \App\Events\NewBid  $event
     * @return void
     */
    public function handle(NewBid $event)
    {
        BidsNotificationController::createBidNotification($event->notification_date, $event->bid_id, $event->users);
    }
}
