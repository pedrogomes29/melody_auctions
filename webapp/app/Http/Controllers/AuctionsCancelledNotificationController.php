<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class AuctionsCancelledNotificationController extends NotificationController
{
    public static function createAuctionCancelledNotification($date,$auctionID,$users)
    {
        $notificationID = parent::create($date,$users);
        DB::table('auctions_cancelled_notifications')->insert(
            [
                'auction_id' => $auctionID,
                'notification_id' => $notificationID
            ]
        );
    }
}

