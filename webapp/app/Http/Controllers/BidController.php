<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Auction;
use App\Models\AuthenticatedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException; 

class BidController extends Controller
{

    /**
     * Creates a new bid.
     *
     * @param  int  $id auction id
     * @param  Request request containing the description
     * @return Response
   */
    public function create(Request $request, $id)
    {
        $bid = new Bid();
        $bid->authenticateduser_id = 1;
        $bid->auction_id = $id;
        $bid->value = $request->input('value'); // input do post

        //$this->authorize('create', $bid);

        // nao é um admin
        //TODO
        try{
            DB::beginTransaction();
            DB::statement('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');

            
            $auction = Auction::find($bid->auction_id);
            $user = AuthenticatedUser::find($bid->authenticateduser_id);

            if($auction->owner_id === $bid->authenticateduser_id)
                throw new PDOException('O utilizador nao pode dar bid na propria auction', 1 );
            

            if ($user->balance < $bid->value)
                throw new PDOException('Insuficient balance to bid that value', 1 );
            

            if($auction->winner_id !== null)
                throw new PDOException('Cannot bid on an auction already won', 1 );
            

            $lastBid = Bid::where('auction_id', $bid->auction_id)->orderByDesc('value')->limit(1)->get()[0];

            if($lastBid->authenticateduser_id === $bid->authenticateduser_id)
                throw new PDOException('Cannot bid on an auction that you are the last person to bid', 1 );
            

            if($auction->startprice > $bid->value)
                throw new PDOException('The bid value must be greater than '.$auction->startprice , 1 );
            
            if ($auction->last_price !== null &&  $auction->last_price + $auction->mindiff > $bid->value)
                throw new PDOException('The bid value must be greater than '.$auction->last_price + $auction->mindiff , 1 );
            

            $bid->id = Bid::max('id') + 1;
            $bid->save();
            
            DB::commit();
        }catch(PDOException $e){
            error_log($e->getMessage());

            DB::rollBack();
        
        }
        
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Http\Response
     */
    public function edit(Bid $bid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bid $bid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bid $bid)
    {
        //
    }
}
