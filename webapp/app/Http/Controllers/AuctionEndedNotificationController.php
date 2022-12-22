<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class AuctionEndedNotificationController extends NotificationController
{
    public static function createAuctionEndedNotification($date,$auctionID,$users)
    {
        $notificationID = parent::create($date,$users);
        DB::table('auctions_ended_notifications')->insert(
            [
                'auction_id' => $auctionID,
                'notification_id' => $notificationID
            ]
        );
    }
}
