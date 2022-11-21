<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;
use Illuminate\Support\Facades\Auth;
class AuthenticatedUserController extends Controller
{
    public function search_results(Request $request){
        $query = $request->search;
        return  response()->json(AuthenticatedUser::select('username','description')
                                                    ->whereRaw('tsvectors @@ to_tsquery(\'english\', ?)', [$query.':*'])
                                                    ->orderByRaw('ts_rank(tsvectors, to_tsquery(\'english\', ?)) DESC', [$query.':*'])
                                                    ->take(10)
                                                    ->get());

    }

}
