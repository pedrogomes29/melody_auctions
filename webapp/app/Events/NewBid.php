<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewBid implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification_date;
    public $auction;
    public $bid;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($date,$auction,$bid){
        $this->notification_date=$date;
        $this->auction = $auction;
        $this->bid = $bid;
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
