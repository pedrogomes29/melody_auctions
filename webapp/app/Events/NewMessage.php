<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;
    public $messageDate;
    private $auctionId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user,$message,$messageDate,$auctionId){
        $this->user= $user;
        $this->message = $message;
        $this->messageDate = $messageDate;
        $this->auctionId = $auctionId;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('auction.'.$this->auctionId);
    }
}

