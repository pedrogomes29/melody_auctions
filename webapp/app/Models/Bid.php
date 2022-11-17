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
        return $this->belongsTo(AuthenticatedUser::class, 'authenticateduser_id');
    }
}
