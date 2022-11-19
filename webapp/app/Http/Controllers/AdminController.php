<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        if (Auth::guard('admin')->user()) {
            $admin = Admin::find($adminId);
            return view('pages.admin')->with('admin', $admin);
        } else {
            return redirect(route('adminLogin'));
        }
    }

    public function auctions($adminId)
    {   
        if (Auth::guard('admin')->user()) {
            $admin = Admin::find($adminId);
            $auctions = DB::table('auctions')->get();
            return view('pages.admin_auctions', compact('adminId', 'auctions'));
        } else {
            return redirect(route('adminLogin'));
        }
   }

    public function edit_auctions($adminId,$auctionId)
    {
        if (Auth::guard('admin')->user()) {
            $auction = DB::table('auctions')->where('id', $auctionId)->first();
            return view('pages.admin_auctions_edit', compact('adminId', 'auction'));
        } else {
            return redirect(route('adminLogin'));
        }
    }

    public function edit_auction(Request $request,$adminId, $auctionId)
    {
        if (Auth::guard('admin')->user()) {
            AuctionController::update($request, $auctionId);
            return redirect('admin/'.$adminId.'/auctions');
        }else {
            return redirect(route('adminLogin'));
        }
    }

    public function delete_auction($adminId,$auctionId)
    {
        if (Auth::guard('admin')->user()) {
            AuctionController::destroy($auctionId);
            return redirect('admin/'.$adminId.'/auctions');
        } else {
            return redirect(route('adminLogin'));
        }
    }

    public function default_image(Request $request,$adminId,$auctionId)
    {
        if (Auth::guard('admin')->user()) {
            $auction = AuctionController::find($auctionId);
            $auction->photo = "";
            $auction->save();
            return view('pages.admin_auctions', compact('adminId', 'auctions'));
        }
        else {
            return redirect(route('adminLogin'));
        }
    }
}
