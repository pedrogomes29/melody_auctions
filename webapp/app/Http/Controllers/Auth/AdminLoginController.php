<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminLoginController extends Controller
{



    public function getLogin(){
      return view('auth.adminlogin');
  }

  public function redirectTo()
  {
      $id = Auth::id();
      return '/admin/'.$id;
  }

  public function postLogin(Request $request)
  {
      $this->validate($request, [
          'email' => 'required|email',
          'password' => 'required',
      ]);
     if(auth()->guard('admin')->attempt(['email' => $request->input('email'),  'password' => $request->input('password')])){
            $user = auth()->guard('admin')->user();
            return redirect()->route('adminDashboard', $user->id);
      }else {
          return back()->with('error','Whoops! invalid email and password.');
      }
  }

  public function adminLogout(Request $request)
  {
      auth()->guard('admin')->logout();
      Session::flush();
      Session::put('success', 'You are logout sucessfully');
      return redirect(route('adminLogin'));
  }
}
