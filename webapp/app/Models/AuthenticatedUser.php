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
    public function reviews(){
        return $this->hasMany(Review::class,'reviewer_id');
    }


    public function followed_auctions() {
        return $this->belongsToMany(Auction::class,'follows');
    }

    public function notifications(){
        return $this->belongsToMany(Notification::class);
    }
}
