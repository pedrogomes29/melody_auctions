<?php

namespace App\Listeners;

use App\Events\AuctionEnding;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAuctionEndingNotification
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
        //
    }
}
