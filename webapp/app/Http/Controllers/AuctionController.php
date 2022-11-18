<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;
use Carbon\Carbon;	
use Illuminate\Support\Str;

class AuctionController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function edit($auctionId)
    {
        $auction = Auction::find($auctionId);
        return view('pages.auction_edit', ['auction' => $auction]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function ownerUpdate(Request $request, $auctionId)
    {
        $success = true;
        $auction = Auction::find($auctionId);

        $validated = $request->validate([
            'name' => 'required|max:50',
            'description' => 'required',
            'minBidDif' => 'required|numeric|min:0.01',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
        ]);

        $auction->name = $request->input('name');
        $auction->description = $request->input('description');
        $auction->minbidsdif = $request->input('minBidDif');

        // Start date
        $inputStartDate = Carbon::parse($request->input('startDate'))->format('Y-m-d H:i:s.u'); // get the start date from the form
        $inputStartDate = substr($inputStartDate, 0, -3); // remove last 3 digits to be according to the dates saved in the database
        $nowDate = Carbon::now()->format('Y-m-d H:i:s.u'); // get current date
        $nowDate = substr($nowDate, 0, -3); // remove last 3 digits to be according to the dates saved in the database
    
        // check if the input date and the auction start date is in the future //check if input date is the current one stored in the database
        if ((Carbon::parse($inputStartDate)->gt(Carbon::now()) && Carbon::parse($auction->startdate)->gt(Carbon::now())) || $auction->startdate == $inputStartDate) { 
            $auction->startdate = $inputStartDate; 
        } else {
            $success = false;
        }

        // End date
        $inputEndDate = Carbon::parse($request->input('endDate'))->format('Y-m-d H:i:s.u'); // get the end date from the form
        $inputEndDate = substr($inputEndDate, 0, -3); // remove last 3 digits to be according to the dates saved in the database

        // check if the input date and the auction start date is in the future //check if input date is the current one stored in the database
        if ((Carbon::parse($inputEndDate)->gt(Carbon::now()) && Carbon::parse($auction->startdate)->gt(Carbon::now())) || $auction->enddate == $inputEndDate) { 
            $auction->enddate = $inputEndDate; 
        } else {
            $success = false;
        }

        
        if ($success){
            $auction->save();
        }
        return view('pages.auction_edit', ['auction' => $auction, 'success' => $success]);
    }

    public function ownerDelete($auctionId)
    {
        $auction = Auction::find($auctionId);
        $auction->delete();
        return redirect('login/');
    }

    public function store(Request $request,$auctionId)
    {
        $auction = Auction::find($auctionId);
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $image_path = $request->file('photo')->store(public_path().'/storage/auctions');
        $image_path = substr($image_path, Str::length(public_path().'/storage/auctions'));
        $auction->photo = $image_path;
        $auction->save();
        return view('pages.auction_edit', ['auction' => $auction]);
    }
}
