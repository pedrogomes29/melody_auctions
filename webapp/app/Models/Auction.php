<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;


    /**
     * Get the owner that owns the Auction.
     */
    public function owner()
    {
        return $this->belongsTo(AuthenticatedUser::class);
    }

    public function bids(){
        return $this->hasMany(Bid::class);
    }

    public function bids_offset(int $offset){
        return $this->bids()->getQuery()->offset($offset*10)->limit(10)->get();
    }

    /**
     * Get the last bidder.
     */
    public function getLastBidder()
    {
        $bid = Bid::where('auction_id', $this->id)->orderByDesc('value')->limit(1)->first();
        
        if($bid === null)
            return null;
        error_log($bid->bidder);
        return $bid->bidder;
    }

    
}
