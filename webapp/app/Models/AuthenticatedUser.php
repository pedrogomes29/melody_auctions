<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthenticatedUser extends Authenticatable
{
    protected $fillable = ['id','email', 'firstname', 'lastname', 'username','password', 'contact','balance','photo'];
    public $timestamps = false;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;


    public function auctions(){
        return $this->hasMany(Auction::class,'owner_id');
    }
    public function reviews(){
        return $this->hasMany(Review::class,'reviewer_id');
    }


    public function followed_auctions() {
        return $this->belongsToMany(Auction::class,'follows')->selectRaw('id,
        CASE 
            WHEN CURRENT_TIMESTAMP < startdate THEN startdate
            ELSE enddate
        END
        as date,
        name AS productName,
        CASE WHEN currentPrice IS NULL
            THEN startPrice
            ELSE currentprice
        END AS minBid,
        currentprice,
        startPrice,
        photo,
        CASE WHEN CURRENT_TIMESTAMP < enddate
            THEN 1
            ELSE 0
        END AS active,
        CASE WHEN CURRENT_TIMESTAMP < startdate
            THEN 1
            ELSE 0
        END AS uninitiated');
    }
    public function notifications(){
        return $this->belongsToMany(Notification::class);
    }

    public function bids(String $order = 'bidsdate', String $direction = 'desc' , int $pageSize = 10){
        if($direction != 'asc' && $direction != 'desc')
            $direction = 'desc';
        if($order != 'bidsdate' && $order != 'value' && $order != 'auction')
            $order = 'bidsdate';

        if($order == 'auction')
            return $this->hasMany(Bid::class,'authenticated_user_id')->join('auctions', 'bids.auction_id', '=', 'auctions.id')
                            ->select('bids.*')->orderBy('auctions.name', $direction)->paginate($pageSize, ['*'], 'bids'); 
        else
            return $this->hasMany(Bid::class,'authenticated_user_id')->orderBy($order, $direction)->paginate($pageSize, ['*'], 'bids');
    }
}
