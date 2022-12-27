<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;


    public $timestamps = false;

    public function sender()
    {
        return $this->belongsTo(AuthenticatedUser::class, 'authenticated_user_id');
    }

    
    public function timeSince(): Attribute{
        return Attribute::make(
            get:function(){
                $seconds = time() - strtotime($this->date);

                $interval = $seconds / 31536000;
              
                if ($interval > 1) {
                  return strval(floor($interval)) . " year". ($interval>=2?'s':'') ." ago";
                }
                $interval = $seconds / 2592000;
                if ($interval > 1) {
                  return strval(floor($interval)) . " month". ($interval>=2?'s':'') ." ago";
                }
                $interval = $seconds / 86400;
                if ($interval > 1) {
                  return strval(floor($interval)) . " day". ($interval>=2?'s':'') ." ago";
                }
                $interval = $seconds / 3600;
                if ($interval > 1) {
                  return strval(floor($interval)) . " hour". ($interval>=2?'s':'') ." ago";
                }
                $interval = $seconds / 60;
                if ($interval > 1) {
                  return strval(floor($interval)) . " minute". ($interval>=2?'s':'') ." ago";
                }
                if($seconds>10)
                    return floor($seconds) . " seconds ago";
                else
                    return "just now";
            }
        );
    }
}
