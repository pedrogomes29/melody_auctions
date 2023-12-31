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
use Illuminate\Support\Facades\Auth;

class NewBid implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification_date;
    public $auction;
    public $bidder;

    public $bid_id;

    public $users;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($date,$auction,$bid){
        $this->notification_date=$date;
        $this->auction = $auction;
        $user = AuthenticatedUser::find($bid->authenticated_user_id);
        $this->bidder = is_null($user)?'DELETED USER':$user->username;
        $this->bid_id = $bid->id;
        $this->users = $this->auction->followers()->where('id', '<>', Auth::id())->get();
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
