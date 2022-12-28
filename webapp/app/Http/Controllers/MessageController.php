<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request, $auctionId){
            $request->validate([
                'text' => 'required'
            ]);

            $message = new Message();
            if (!Auth::check())
                return response('You must be logged in to send a message', 401);

            $message->authenticated_user_id = Auth::id();
            $message->auction_id = $auctionId;
            $message->text = $request->text;
            $message->id = Message::max('id')+1;
            $message->save();
        

            NewMessage::dispatch(Auth::user(),$request->text,now(),$auctionId);
            return response()->json('Successfully sent message', 200);

    }
}
