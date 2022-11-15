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
}
