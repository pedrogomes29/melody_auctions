<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
class AuctionController extends Controller
{
    public function search_results(Request $request){
        $query = $request->search;
        return  response()->json(Auction::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
                                        ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                                        ->get());
                                        
    }
}
