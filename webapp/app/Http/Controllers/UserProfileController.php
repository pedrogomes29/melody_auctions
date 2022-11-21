<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;
use Illuminate\Support\Facades\Auth;
use App\Policies\AuthenticatedUserPolicy;
class UserProfileController extends Controller
{
    public function showUserProfile($username)
    {
        $auctions_owned = AuthenticatedUser::where('username', $username)
                                            ->firstOrFail()
                                            ->auctions()
                                            ->selectRaw('  id,
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
                                            ->get();
        $user = AuthenticatedUser::where('username',$username)->firstOrFail();

        return view('pages.user', ['user' => $user, 'auctions' => $auctions_owned]);
    }

    public function updateUserProfile(Request $request, $username)
    {
        $id = AuthenticatedUser::where('username',$username)->firstOrFail()->id;
        if (!Auth::check() || !Auth::user()->can('update', AuthenticatedUser::find($id))) {
            return redirect()->route('home');
        }
        $user = AuthenticatedUser::where('username',$username)->firstOrFail();
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
    public function updateUserBalance(Request $request, $username)
    {
        $id= AuthenticatedUser::where('username',$username)->firstOrFail()->id;
        if (!Auth::check() || !Auth::user()->can('update', AuthenticatedUser::find($id))) {
            return redirect()->route('home');
        }
        $data = $request->validate([
            'balance' => 'required|numeric',
        ]);
        $user = AuthenticatedUser::where('username',$username)->firstOrFail();
        $data = request()->validate([
            'balance' => 'required|numeric',
        ]);
        $user->balance = $data['balance'] + $user->balance;
        $user->save();
        return view('pages.user', ['user' => $user]);
    }
    public function deleteUserProfile($username)
    {
        $id = AuthenticatedUser::where('username',$username)->firstOrFail()->id;
        if (!Auth::check() || !Auth::user()->can('delete', AuthenticatedUser::find($id))) {
            return redirect()->route('home');
        }
        $user = AuthenticatedUser::where('username',$username)->firstOrFail();
        $user->delete();
        return redirect()->route('login');
    }
    public function store(Request $request, $username)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $image_path = $request->file('image')->store('image', 'public');
        $user = AuthenticatedUser::where('username',$username)->firstOrFail();
        $user->photo = $image_path;
        $user->save();
        session()->flash('success', 'Image Upload successfully');
        return redirect()->route('user', ['username' => $user->username]);
    }

}
