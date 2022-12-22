<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class AuctionEndingNotificationController extends NotificationController
{
    public static function createAuctionEndingNotification($date,$auctionID,$users)
    {
        $notificationID = parent::create($date,$users);
        DB::table('auctions_ending_notifications')->insert(
            [
                'auction_id' => $auctionID,
                'notification_id' => $notificationID
            ]
        );
    }
}
