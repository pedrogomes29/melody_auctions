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
                                                    ->whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$query])
                                                    ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$query])
                                                    ->take(10)
                                                    ->get());

    }


    public static function get_username_pfp(){
        $profile_pic_path="";
        $username="";
        if(Auth::check()){
            global $profile_pic_path,$username;
            $aux = AuthenticatedUser::select('username','photo')
                                                    ->where('id','=',Auth::id())
                                                    ->firstOrFail();
            $profile_pic_path = $aux->photo;
            $username = $aux->username;
        }


        return [$username,$profile_pic_path];
    }

}
