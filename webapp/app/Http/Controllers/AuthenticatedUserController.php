<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;
class AuthenticatedUserController extends Controller
{
    public function search_results(Request $request){
        $query = $request->search;
        return  response()->json(AuthenticatedUser::whereRaw('tsvectors @@ plainto_tsquery(\'english\',?)',[$query])
                                        ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                                        ->get());
                                        

    }
}
