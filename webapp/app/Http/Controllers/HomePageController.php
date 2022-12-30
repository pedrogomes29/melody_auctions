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


        $uninitiated_auctions = Auction::selectRaw('id,
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
                                                    ->where('startdate','>',now())
                                                    ->where('cancelled','<>',1)
                                                    ->take(10)
                                                    ->get();;



        $active_auctions = Auction::selectRaw(' id,
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
                                                ->where('enddate','>',now())
                                                ->where('startdate','<',now())
                                                ->where('cancelled','<>',1)
                                                ->take(10)
                                                ->get();

        $closed_auctions = Auction::selectRaw(' id,
                                                CASE 
                                                    WHEN CURRENT_TIMESTAMP < startdate THEN startdate
                                                    ELSE enddate
                                                END
                                                as date,
                                                name AS productName,
                                                CASE WHEN currentPrice IS NULL
                                                    THEN currentprice
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
                                                ->where('enddate','<',now())
                                                ->where('cancelled','<>',1)
                                                ->take(10)
                                                ->get();


        $categories = Category::orderBy('name')
                                ->get();

        return view('pages.index')
                ->with('uninitiated_auctions',$uninitiated_auctions)
                ->with('active_auctions',$active_auctions)
                ->with('closed_auctions',$closed_auctions)
                ->with('categories',$categories);
    }
}
