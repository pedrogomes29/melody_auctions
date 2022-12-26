<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Bid;

class NewBidNotification implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $bid;

  public function __construct(Bid $bid)
  {
      $this->bid = $bid;
      $bidder = $bid->bidder;
    
  }

  public function broadcastOn()
  {
      return ['bids-channel'];
  }

  public function broadcastAs()
  {
      return 'new_bid-event';
  }
}