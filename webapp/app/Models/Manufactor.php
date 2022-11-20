<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufactor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'id'];  
    
    public $timestamps = false;
}
