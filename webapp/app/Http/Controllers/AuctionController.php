<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;


class AuctionController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCreate()
    {
        //TODO verificar se está logado
        return view('pages.create_auction');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //TODO verificar se está logado
        return view('pages.create_auction');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $auction = Auction::find($id);
        if($auction){
            //$this->authorize('show', $auction);
            return view('pages.auction', ['auction' => $auction]);
        }else{
            abort(404);
        }
    }

    public function bids(Request $request, $id){

        $offset =$request->offset;
        if($offset!==null){
            // Validate if a string is a valid number

            if(! preg_match('/^[0-9]+$/', $offset)){
                abort(404);
            }   
        }

        $auction = Auction::find($id);
        if($auction==null){
            abort(404);
        }

        $bids = [];
        if($offset!==null){
            $bids = $auction->bids_offset(intval($offset));
        }else{
            $bids =$auction->bids;
        }
        
        error_log($bids);
        return view('partials.bids', ['bids' => $bids]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function edit(Auction $auction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Auction $auction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Auction $auction)
    {
        //
    }
}
