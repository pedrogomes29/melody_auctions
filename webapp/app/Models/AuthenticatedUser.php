<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AuthenticatedUser extends Authenticatable
{
    protected $fillable = ['id','email', 'firstname', 'lastname', 'username','password', 'contact','balance','photo'];
    public $timestamps = false;
    use HasFactory;

    public function auctions(){
        return $this->hasMany(Auction::class,'owner_id');
    }


    public function followed_auctions() {
        return $this->belongsToMany(Auction::class,'follows');
    }


    public function bids(String $order = 'bidsdate', String $direction = 'desc' , int $pageSize = 1){
        $bids_pag = $this->hasMany(Bid::class,'authenticated_user_id')->orderBy($order, $direction)->paginate($pageSize, ['*'], 'bids');
        
        return $bids_pag;
    }
}
