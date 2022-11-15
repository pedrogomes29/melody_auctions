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
    public function updateUserProfile(Request $request, $username)
    {
        $id = AuthenticatedUser::where('username',$username)->firstOrFail()->id;
        $user = AuthenticatedUser::where('username',$username)->firstOrFail();
        if(Auth::id() == $id)
        {
            $user->email = $request->input('email');
            $user = AuthenticatedUser::where('username',$username)->firstOrFail();;
            $user->firstname = $request->input('firstname');
            $user->lastname = $request->input('lastname');
            $user->username = $request->input('username');
            $user->description = $request->input('description');
            $user->contact = $request->input('contact');
            $user->save();
            return redirect()->route('user', ['username' => $user->username]);

        }
        else
        {
            return redirect()->route('home');
        }
    }
    public function updateUserBalance(Request $request, $username)
    {
        $id = AuthenticatedUser::where('username',$username)->firstOrFail()->id;
        $user = AuthenticatedUser::where('username',$username)->firstOrFail();
        if(Auth::id() == $id)
        {
            $user->balance = $request->input('balance')+ $user->balance;
            $user->save();
            return redirect()->route('user', ['username' => $user->username]);
        }
        else
        {
            return redirect()->route('home');
        }
    }
}
