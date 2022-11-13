<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin/home';

    public function __construct()
    {
      $this->middleware('guest')->except('logout');
    }

    protected function guard()
    {
     return Auth::guard('admin');
    }
    public function showLoginForm()
    {
        return view('auth.adminlogin');
    }
}
