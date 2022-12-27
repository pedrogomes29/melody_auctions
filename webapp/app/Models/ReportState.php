<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportState extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'reports_states';
}
