<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\AuthenticatedUser;
use Exception;

class NotificationController extends Controller
{

    public static function create($date,$users)
    {
        $notification = new Notification();
        $notification->date=now();
        $notification->beenread=false;
        $notification->id = Notification::max('id') + 1;
        $notification->save();
        foreach($users as $user){
            $user->notifications()->save($notification);
        }
        return $notification->id;
    }

    public function markAsRead($userId)
    {
        try{
            $userNotifications = AuthenticatedUser::find($userId)
                                ->notifications()
                                ->get();
        
            foreach($userNotifications as $userNotification){
                $userNotification->beenread=true;
                $userNotification->save();
            }
        }
        catch(Exception $e){
            return response()->json($e, 500);
        }

        return response()->json('Marked Notifications as read', 200);
    }
}
