<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Auction;
use App\Models\AuthenticatedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException; 
use Auth;
use App\Http\WebSockets\NewBidNotification;

class BidController extends Controller
{

    /**
     * Creates a new bid.
     *
     * @param  int  $id auction id
     * @param  Request request containing the description
     * @return Response
   */
    public function create(Request $request, int $id)
    {

        
        $this->authorize('create', Bid::class);
        $bid = new Bid();
        $bid->authenticated_user_id = Auth::id();
        $bid->auction_id = $id;
        $bid->value = $request->input('value'); // input do post

        

        // nao é um admin
        //TODO passar isto para o authorize ???
        try{
            DB::beginTransaction();
            DB::statement('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');

            
            $auction = Auction::find($bid->auction_id);
            if (empty($auction)){
                DB::rollBack();
                abort(404);
                return;
            }

            $user = AuthenticatedUser::find($bid->authenticated_user_id);
            if (empty($user)){
                DB::rollBack();
                abort(404);
            }

            if($auction->owner_id === $bid->authenticated_user_id)
                throw new PDOException('Cannot bid on your own auction!', 1 );
            

            if ($user->balance < $bid->value)
                throw new PDOException('Insuficient balance to bid that value!', 1 );
            

            if($auction->winner_id !== null)
                throw new PDOException('Cannot bid on an auction already won!', 1 );
            

            $lastBid = Bid::where('auction_id', $bid->auction_id)->orderByDesc('value')->limit(1)->first();

            if($lastBid && $lastBid->authenticated_user_id === $bid->authenticated_user_id)
                throw new PDOException('Cannot bid on an auction that you are the last person to bid!', 1 );

            
            if ($auction->currentprice !== null &&  $auction->currentprice + $auction->minbidsdif > $bid->value)
                throw new PDOException('The bid value must be greater than '.$auction->currentprice + $auction->minbidsdif.'!' , 1 );
            
            if($auction->startprice > $bid->value)
                throw new PDOException('The bid value must be greater than '.$auction->startprice.'!' , 1 );
            

            $bid->id = Bid::max('id') + 1;
            $bid->save();
            
            DB::commit();
            event(new NewBidNotification( $bid )); // send bid notification to all users in the auction
        
        // guarda na base de dados
        }catch(PDOException $e){
            error_log($e->getMessage());
            
            DB::rollBack();
            return redirect()->back()->withErrors(['bid_error' => $e->getMessage()]);
        }
        

        return redirect()->back()->withErrors(['bid_success' => 'The bid was successfully made! :)']);
    }

}
