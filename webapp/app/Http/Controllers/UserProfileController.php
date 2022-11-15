<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;
use Illuminate\Support\Facades\Auth;
class UserProfileController extends Controller
{
    public function showUserProfile($username)
    {
        $user = AuthenticatedUser::where('username',$username)->firstOrFail();;
        return view('pages.user', ['user' => $user]);
    }
    public function showUserEditForm($username)
    {
        $id = AuthenticatedUser::where('username',$username)->firstOrFail()->id;
        if(Auth::id() == $id)
        {
            $user = AuthenticatedUser::where('username',$username)->firstOrFail();;
            return view('pages.user-edit', ['user' => $user]);
        }
        else
        {
            return redirect()->route('home');
        }
    }
}
