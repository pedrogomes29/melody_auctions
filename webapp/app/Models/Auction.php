<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function owner() {
        return $this->belongsTo('App\Models\AuthenticatedUser', 'owner_id');
    }
}
