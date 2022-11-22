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
                                            ->selectRaw('id,
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
                                                        END AS uninitiated,
                                                        cancelled')
                                            ->take(10)
                                            ->get();
        
        $user = AuthenticatedUser::where('username',$username)->firstOrFail();

        return view('pages.user', ['user' => $user, 'auctions' => $auctions_owned]);
    }

    public function showUserAuctions($username){
        $auctions_owned = AuthenticatedUser::where('username', $username)
                                            ->firstOrFail()
                                            ->auctions()
                                            ->selectRaw('id,
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
                                                        END AS uninitiated,
                                                        cancelled')
                                            ->get();

        return view('pages.user_auctions', ['auctions' => $auctions_owned]);
    }

    public function updateUserProfile(Request $request, $username)
    {
        $id = AuthenticatedUser::where('username',$username)->firstOrFail()->id;
        if (! Auth::guard('admin')->user() && (!Auth::check() || !Auth::user()->can('update', AuthenticatedUser::find($id)))) {
            return redirect()->route('home');
        }
        $user = AuthenticatedUser::where('username',$username)->firstOrFail();
        $user->email = $request->input('email');
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->username = $request->input('username');
        $user->description = $request->input('description');
        $user->contact = $request->input('contact');
        error_log("USER");
        error_log($user);
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
        // TROCAMOS PQ NO SERVIDOR DE LBAW APAGA AS FOTOS DE 30 EM 30 MIN
        // $image_path = $request->file('image')->store('image', 'public');
        
        $file = $request->file('image');
        $image_path= date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('images/profile'), $image_path);

        $user = AuthenticatedUser::where('username',$username)->firstOrFail();
        $user->photo = 'images/profile/'.$image_path;
        $user->save();
        session()->flash('success', 'Image Upload successfully');
        return redirect()->route('user', ['username' => $user->username]);
    }

}
