<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
   

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show($adminId)
    {
        $admin = Admin::find($adminId);
        return view('pages.admin')->with('admin', $admin);
    }

    public function auctions($adminId)
    {   
        $auctions = AuctionController::findAll();
        return view('pages.admin_auctions')->with(compact('adminId','auctions'));
    }

    public function edit_auctions($adminId,$auctionId)
    {
        $auction = AuctionController::find($auctionId);
        return view('pages.admin_auctions_edit')->with(compact('adminId','auction'));
    }

    public function edit_auction(Request $request,$adminId, $auctionId)
    {
        AuctionController::update($request, $auctionId);
        return redirect('admin/'.$adminId.'/auctions');
    }

    public function delete_auction($adminId,$auctionId)
    {
        AuctionController::destroy($auctionId);
        return redirect('admin/'.$adminId.'/auctions');;
    }
}
