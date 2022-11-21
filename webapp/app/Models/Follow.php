<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;
    protected $fillable = ['authenticated_user_id','auction_id'];
    protected $primaryKey = ['authenticated_user_id','auction_id'];
    public $incrementing = false;
    public $timestamps = false;
}
