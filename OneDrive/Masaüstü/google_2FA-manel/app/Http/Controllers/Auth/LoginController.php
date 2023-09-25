<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use RobThree\Auth\TwoFactorAuth;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
        echo Auth::check();
    }

    public function processLogin(Request $request)
    {
        // Perform your authentication logic here (e.g., check user credentials)
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email', $email)
        ->first();

        if ($user && password_verify($password, $user->password)) {
            // User exists
            $request->session()->put('is_logged_in', true);
            $request->session()->put('user_id', $user['id']);
            return redirect()->route('dashboard');
        } else {
            // User doesn't exist
            return "User with username $email does not exist!";
        }


    }

    public function logout(Request $request)
    {
        // Clear the session variable to indicate the user is logged out
        $request->session()->forget('is_logged_in');

        // Redirect the user to the login form
        return redirect()->route('login');
    }
    public function checkTotp(Request $request)
    {
        $userid = request()->session()->get('user_id');
        $user = User::where('id', $userid)
        ->first();
        //echo $user;
        if($user['token']){
             $userInput = $request->input('one_time_password');
             $tfa = new TwoFactorAuth('GOOGLE_2FA');
            $totpCode = $tfa->getCode($user['token']);

            $isTotpValid = $tfa->verifyCode($user['token'], $userInput);
            if ($isTotpValid) {
              return view('home');
                //return redirect()->route('authenticated.dashboard');
            } else {

                return view('google2fa.index')->withErrors(['totp' => 'Invalid TOTP']);
            }
        }

        //
    }
}
