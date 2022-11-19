<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Category;
use App\Models\AuthenticatedUser;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomePageController extends Controller
{

    public function index(Request $request){

        $active_auctions = Auction::selectRaw(' id,
                                                enddate,
                                                CASE 
                                                WHEN CURRENT_TIMESTAMP < enddate
                                                    THEN 1
                                                    ELSE 0
                                                    END
                                                AS active,
                                                name AS productName,
                                                currentprice + minbidsdif AS minBid,
                                                photo')
                                    ->where('enddate','>',now())
                                    ->take(10)
                                    ->get();

        $closed_auctions = Auction::selectRaw(' id,
                                                enddate,
                                                CASE 
                                                WHEN CURRENT_TIMESTAMP < enddate
                                                    THEN 1
                                                    ELSE 0
                                                END
                                                AS active,
                                                name AS productName,
                                                currentprice + minbidsdif AS minBid,
                                                photo')
                                    ->where('enddate','<',now())
                                    ->take(10)
                                    ->get();


        $categories = Category::select('name','id')
                                ->orderBy('name')
                                ->get();

        return view('pages.index')
                ->with('active_auctions',$active_auctions)
                ->with('closed_auctions',$closed_auctions)
                ->with('categories',$categories);
    }
}
