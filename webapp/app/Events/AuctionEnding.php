<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\AuthenticatedUser;


class AuctionEnding implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification_date;
    public $auction;
    public $users;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($date,$auction){
        $this->notification_date=$date;
        $this->auction = $auction;
        $this->users = $auction->followers()->get();
        if(!$this->users->contains('id',$this->auction->owner_id))
            $this->users->push(AuthenticatedUser::find($this->auction->owner_id));
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {

        $channels = [];
        foreach($this->users as $user){
            array_push($channels,new PrivateChannel('users.'.$user->id));
        }

        return $channels;
        
    }
}
