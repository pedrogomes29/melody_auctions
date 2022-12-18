<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuctionCancelled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification_date;
    public $auction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($date,$auction){
        $this->notification_date=$date;
        $this->auction = $auction;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {

        $channels = [new PrivateChannel('users.'.$this->auction->owner_id)];
        foreach($this->auction->followers()->get() as $follower){
            array_push($channels,new PrivateChannel('users.'.$follower->id));
        }

        return $channels;
    }
}   
