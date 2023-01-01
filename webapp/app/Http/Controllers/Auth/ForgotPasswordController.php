<?php 
  
namespace App\Http\Controllers\Auth; 
  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use DB; 
use Carbon\Carbon; 
use App\Models\User; 
use Illuminate\Support\Facades\Password;
use Mail; 
use Hash;
use Illuminate\Support\Str;
  
class ForgotPasswordController extends Controller
{
      public function showEmailForm()
      {
        return view('auth.forgot-password');
      }

      public function storePasswordTokenAndSendEmail(Request $request){
        $request->validate(['email' => 'required|email']);
 
        $status = Password::sendResetLink(
            $request->only('email')
        );
     
        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);

      }

      public function showRecoverPasswordForm(Request $request, $token) {
        $email = $request->email;
        return view('auth.reset-password')->with('token',$token)
                                          ->with('email',$email);
    }

    public function resetPassword (Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ]);
     
                $user->save();
             }
        );
     
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }


}