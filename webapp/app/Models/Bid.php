<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * Get the  bidder.
     */
    public function bidder(){
        return $this->belongsTo(AuthenticatedUser::class, 'authenticated_user_id');
    }

    /**
     * Get the  auction.
     */
    public function auction(){
        return $this->belongsTo(Auction::class, 'auction_id');
    }
}
