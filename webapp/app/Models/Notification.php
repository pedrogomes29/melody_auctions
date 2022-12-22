<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function auction(): Attribute
    {
        return Attribute::make(
            get: function(){

                $child = $this->hasOne(AuctionsCancelledNotification::class)->first()
                ?: ($this->hasOne(AuctionsEndedNotification::class)->first()
                ?: $this->hasOne(AuctionsEndingNotification::class)->first());
                
                if($child){
                    return Auction::find($child->auction_id);
                }
                else{
                    $child = $this->hasOne(BidsNotification::class)->first();
                    $bid = Bid::find($child->bid_id);
                    return Auction::find($bid->auction_id);
                }
            }
        );
    }

    public function bidder(): Attribute{
        return Attribute::make(
            get:function(){
                $bidNotification = $this->hasOne(BidsNotification::class)->first();
                $bid = Bid::find($bidNotification->bid_id);
                return AuthenticatedUser::find($bid->authenticated_user_id);
            }
        );
    }

    public function type(): Attribute{
        return Attribute::make(
            get:function(){
                if($this->hasOne(AuctionsCancelledNotification::class)->first())
                    return 'AuctionCancelled';
                if($this->hasOne(AuctionsEndedNotification::class)->first())
                    return 'AuctionEnded';
                if($this->hasOne(AuctionsEndingNotification::class)->first())
                    return 'AuctionEnding';
                if($this->hasOne(BidsNotification::class)->first())
                    return 'Bid';
                }
        );
    }

    public function timeSince(): Attribute{
        return Attribute::make(
            get:function(){
                $seconds = time() - strtotime($this->date);

                $interval = $seconds / 31536000;
              
                if ($interval > 1) {
                  return strval(floor($interval)) . " year". ($interval>=2?'s':'') ." ago";
                }
                $interval = $seconds / 2592000;
                if ($interval > 1) {
                  return strval(floor($interval)) . " month". ($interval>=2?'s':'') ." ago";
                }
                $interval = $seconds / 86400;
                if ($interval > 1) {
                  return strval(floor($interval)) . " day". ($interval>=2?'s':'') ." ago";
                }
                $interval = $seconds / 3600;
                if ($interval > 1) {
                  return strval(floor($interval)) . " hour". ($interval>=2?'s':'') ." ago";
                }
                $interval = $seconds / 60;
                if ($interval > 1) {
                  return strval(floor($interval)) . " minute". ($interval>=2?'s':'') ." ago";
                }
                if($seconds>10)
                    return floor($seconds) . " seconds";
                else
                    return "just now";
            }
        );
    }
}
