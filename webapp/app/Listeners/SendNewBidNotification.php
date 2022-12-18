<?php

namespace App\Listeners;

use App\Events\NewBid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewBidNotification
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
        //
    }
}
