<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Auction;
use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function show(Follow $follow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function edit(Follow $follow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Follow $follow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function destroy(Follow $follow)
    {
        //
    }
    public function showFollows($username){
        $id = AuthenticatedUser::where('username', $username)->first()->id;
        $follows = Follow::where('authenticateduser_id', $id)->get();
        $auctions = [];
        foreach($follows as $follow){
            array_push($auctions, Auction::selectRaw(' id,
            enddate,
            CASE 
            WHEN CURRENT_TIMESTAMP < enddate
                THEN 1
                ELSE 0
                END
            AS active,
            name AS productName,
            currentprice + minbidsdif AS minBid,
            photo')->where('id', $follow->auction_id)->first());
        }
        return view('pages.follows')->with('auctions', $auctions)->with('username', $username);
    }
}
