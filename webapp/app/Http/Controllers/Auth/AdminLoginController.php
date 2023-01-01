<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\Admin;

class AdminLoginController extends Controller
{

    public function getLogin(){
      if(auth()->user()){
        return redirect(route('home'));
      }
      return view('auth.adminlogin');
  }

  public function redirectTo()
  {
      $id = Auth::id();
      $username = Admin::where('id',$id)->firstOrFail()->username;
      return '/admin/'.$username;
  }

  public function postLogin(Request $request)
  {
    if(auth()->user()){
        return redirect(route('home'));
      }
      $this->validate($request, [
          'email' => 'required|email',
          'password' => 'required',
      ]);
     if(auth()->guard('admin')->attempt(['email' => $request->input('email'),  'password' => $request->input('password')])){
            $user = auth()->guard('admin')->user();
            $username = Admin::where('id',$user->id)->firstOrFail()->username;
            return redirect()->route('adminDashboard', $username);
      }else {
          return back()->with('error','Whoops! invalid email and password.');
      }
  }

  public function adminLogout(Request $request)
  {
    if(auth()->user()){
        return redirect(route('home'));
      }
      auth()->guard('admin')->logout();
      Session::flush();
      Session::put('success', 'You are logout sucessfully');
      return redirect(route('adminLogin'));
  }
}
