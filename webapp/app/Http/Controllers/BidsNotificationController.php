<?php

namespace App\Http\Controllers;

use App\Models\BidsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidsNotificationController extends NotificationController
{
    public static function createBidNotification($date,$bidID,$users)
    {
        $notificationID = parent::create($date,$users);
        DB::table('bids_notifications')->insert(
            [
                'bid_id' => $bidID,
                'notification_id' => $notificationID
            ]
        );
    }
}
