<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\AuthenticatedUser;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    public function redirectTo()
    {
        $id = Auth::id();
        $username = AuthenticatedUser::where('id',$id)->firstOrFail()->username;
        return '/user/'.$username;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function guard()
    {
     return Auth::guard('authenticateduser');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function logout()
    {
        Auth::guard('authenticateduser')->logout();
        return redirect('/login');
    }

}
