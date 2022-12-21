<?php

namespace App\Http\Controllers;

use App\Models\AuctionsCancelledNotification;
use Illuminate\Support\Facades\DB;
class AuctionsCancelledNotificationController extends NotificationController
{
    public function create($date,$auctionID)
    {
        $notificationID = parent::create($date);
        $auctionCancelledNotification = new AuctionsCancelledNotification();
        $auctionCancelledNotification->notification_id=$notificationID;
        $auctionCancelledNotification->auction_id=$auctionID;
        $auctionCancelledNotification->save();
    }
}

