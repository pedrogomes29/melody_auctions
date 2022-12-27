<?php

namespace App\Providers;

use App\Events\AuctionCancelled;
use App\Events\AuctionEnded;
use App\Events\AuctionEnding;
use App\Events\NewBid;
use App\Listeners\StoreAuctionCancelledNotification;
use App\Listeners\StoreAuctionEndedNotification;
use App\Listeners\StoreAuctionEndingNotification;
use App\Listeners\StoreBidNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AuctionCancelled::class=>[
            StoreAuctionCancelledNotification::class
        ],
        AuctionEnded::class=>[
            StoreAuctionEndedNotification::class
        ],
        AuctionEnding::class=>[
            StoreAuctionEndingNotification::class
        ],
        NewBid::class=>[
            StoreBidNotification::class
        ],
        NewMessage::class=>[]

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
