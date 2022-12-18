<?php

use App\Events\AuctionEnded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAuctionEndedNotification
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
     * @param  \App\Providers\AuctionEnded  $event
     * @return void
     */
    public function handle(AuctionEnded $event)
    {
        //
    }
}
