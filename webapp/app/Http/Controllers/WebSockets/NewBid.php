<?php
namespace App\Http\Controllers\WebSockets;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewBid implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $bid;

  public function __construct(Bid $bid)
  {
      $this->message = $message;
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