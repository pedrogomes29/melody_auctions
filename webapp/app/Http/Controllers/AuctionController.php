<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public static function update(Request $request, $id)
    {
        $auction = Auction::find($id);
        
        $auction->name = $request->input('name');
        $auction->description = $request->input('description');
        $auction->save();
        return redirect('admin/auctions');
    }

    public static function destroy($id)
    {
        $auction = Auction::find($id);
        $auction->delete();
        return redirect('admin/auctions');
    }
  
    public static function find($id)
    {
        $auction = Auction::find($id);
        return $auction;
    }

    public static function findAll()
    {
        $auctions = Auction::all();
        return $auctions;
    }
}
