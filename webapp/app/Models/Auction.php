<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Auction extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * Get the owner that owns the Auction.
     */
    public function owner()
    {
        return $this->belongsTo(AuthenticatedUser::class);
    }

    public function bids(){
        return $this->hasMany(Bid::class)->orderBy('value', 'desc');
    }

    public function followers() {
        return $this->belongsToMany(AuthenticatedUser::class,'follows');
    }

    public function bids_offset(int $offset){
        return $this->bids()->getQuery()->offset($offset*10)->limit(10)->get();
    }

    public function isClosed(){
        $now = new DateTime(now());
        $end = new DateTime($this->enddate);
        return $end < $now;
    }

    public function isOpen(){
        $start = new DateTime($this->startdate);
        $now = new DateTime(now());
        $end = new DateTime($this->enddate);

        return $start <= $now && $end > $now;
    }

    public function notStarted(){
        $start = new DateTime($this->startdate);
        $now = new DateTime(now());
        return $start > $now;
    }



    /**
     * Get the last bidder.
     */
    public function getLastBidder()
    {
        $bid = Bid::where('auction_id', $this->id)->orderByDesc('value')->limit(1)->first();
        
        if($bid === null)
            return null;
        return $bid->bidder;
    }

    
}
