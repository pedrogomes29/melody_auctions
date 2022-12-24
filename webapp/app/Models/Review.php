<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public function owner()
    {
        return $this->belongsTo(AuthenticatedUser::class, 'reviewer_id');
    }
    use HasFactory;
    protected $fillable = ['reviewer_id','reviewed_id','rating','comment'];
    public $timestamps = false;
}
