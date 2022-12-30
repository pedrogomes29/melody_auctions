<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuthenticatedUser;
use Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleCallback()
    {
        try {
        $user = Socialite::driver('google')->stateless()->user();
        } catch (Exception $e) {
            return redirect('/login');
        }
        //check if user already exists
        $existingUser = AuthenticatedUser::where('email', $user->email)->first();
        if($existingUser){
            //log them in
            auth()->login($existingUser, true);
        } else {
            //create a new user
            $newUser = new AuthenticatedUser;
            $newUser->id = AuthenticatedUser::max('id') + 1;
            //generate a random username
            $newUser->username = "user".$newUser->id * rand(1,1000);
            $newUser->firstname = $user->name;
            $newUser->lastname =  " ";
            $newUser->email = $user->email;
            $newUser->save();
            auth()->login($newUser, true);
        }
        return redirect()->to('/');
    }
}
