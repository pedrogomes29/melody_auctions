<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Report;
use App\Models\AuthenticatedUser;
use App\Models\ReportState;
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
    public function show($adminUserName)
    {
        if (Auth::guard('admin')->user()) {
            $admin = Admin::where('username', $adminUserName)->firstOrFail();
            $reports = Report::select('id','reportstext','reportsdate','reports_state_id','reported_id','reporter_id','reports_state_id')
                                ->orderBy('reportsdate', 'desc')
                                ->get();
            foreach ($reports as $report) {
                $report->reporter = AuthenticatedUser::select('username')->where('id', $report->reporter_id)->first()->username;
                $report->reported = AuthenticatedUser::select('username')->where('id', $report->reported_id)->first()->username;
                $report->state = ReportState::select('state')->where('id', $report->reports_state_id)->first()->state;
            }

            $categories = Category::select('name','id')
                          ->orderBy('name')
                          ->get();

            return view('pages.admin')
                ->with('admin', $admin)
                ->with('reports', $reports)
                ->with('categories', $categories);
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
            $auctions = AuctionController::findAll();
            return view('pages.admin_auctions')->with('adminId', $adminId)->with('auctions', $auctions);
        }
        else {
            return redirect(route('adminLogin'));
        }
    }

    public function closeReport(Request $request){
        if (Auth::guard('admin')->user()) {
            $report = Report::find($request->reportId);
            $report->reports_state_id = 2;
            $report->save();
            return redirect()->back();
        }
        else {
            return redirect(route('adminLogin'));
        }
    }
}
