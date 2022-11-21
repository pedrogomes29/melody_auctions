<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Auction;
use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;
use Illuminate\Support\Facades\Auth;
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
        $this->authorize('create', Follow::class);
        $follow = new Follow();
        $follow->authenticated_user_id = Auth::id();
        $follow->auction_id = $request->input('auction_id');
        $follow->save();
        return redirect()->route('auction.show', ['auction_id' => $request->input('auction_id')]);
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
    public function destroy(Request $request)
    {
        $this->authorize('delete', Follow::class);
        $follow = Follow::where('authenticated_user_id', Auth::id())->where('auction_id', $request->input('auction_id'));
        $follow->delete();
        return redirect()->route('auction.show', ['auction_id' => $request->input('auction_id')]);
    }
    public function showFollows($username){
        $auctions = AuthenticatedUser::where('username', $username)
                                    ->firstOrFail()
                                    ->followed_auctions()
                                    ->selectRaw('id,
                                                CASE 
                                                    WHEN CURRENT_TIMESTAMP < startdate THEN startdate
                                                    ELSE enddate
                                                END
                                                as date,
                                                name AS productName,
                                                CASE WHEN currentPrice IS NULL
                                                    THEN startPrice
                                                    ELSE currentprice+minbidsdif
                                                END AS minBid,
                                                photo,
                                                CASE WHEN CURRENT_TIMESTAMP < enddate
                                                    THEN 1
                                                    ELSE 0
                                                END AS active,
                                                CASE WHEN CURRENT_TIMESTAMP < startdate
                                                    THEN 1
                                                    ELSE 0
                                                END AS uninitiated')
                                    ->get();
        return view('pages.follows')->with('auctions', $auctions)->with('username', $username);
    }
}
