<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use RobThree\Auth\TwoFactorAuth;

class AuthController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function register(Request $request )
    {
        $validator = validator ::make($request-> all(),[
            'name'=> 'required',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required'
        ]);
        if($validator->fails()){
           return response()->json(['status_code'=> 400 ,'message'=>'Bad Request']);

        }
       $google2fa = app('pragmarx.google2fa');
        $tokenResult= $google2fa->generateSecretKey();

        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
        $user->token =  $tokenResult;
        $user->totp_verified = false;
        $user->save();
       // $tokenResult=$user->createToken('authToken')->plainTextToken;
       $QR_Image = $google2fa->getQRCodeInline(
        config('app.name'),
       $request->email,
       $tokenResult
    );

        return view('google2fa.register',['QR_Image'=>$QR_Image,'secret'=>$tokenResult]);

    }

    public function login(Request $request){
        $validator = validator ::make($request-> all(),[
            'email'=> 'required|email',
            'password'=> 'required'
        ]);
        echo Auth::check();
        if($validator->fails()){
            return response()->json(['status_code'=> 400 ,'message'=>'Bad Request']);

         }

         $credentials=request(['email','password']);

         if(!Auth::attempt($credentials))
         {
            return response()->json([
                'status_code'=>500,
                'message'=> 'Unauthorized'
            ]);
         }
         $user = User::where ('email',$request->email)->first();
                  $request->session()->put('user_id', $user['id']);

        /* return response()->json ([
            'status_code'=>200,

         ]);*/
        // return view('google2fa.index');
       
        }
         public function logout (Request $request)
         {
            //$request->user()->currentAccessToken()->revoke();
            /*return response()->json ([
                'status_code'=>200,
                'token'=>'token deleted successfully'
             ]);*/
             Auth::guard('web')->logout();
             
              $userid = request()->session()->get('user_id');
        $user = User::where('id', $userid)
        ->first();
        $user->totp_verified = false;
        $user->save(); 
             return redirect()->route('login');
         }



    public function checkTotp(Request $request)
    {
      
       $userid = request()->session()->get('user_id');
       $user = User::where('id', $userid)
       ->first();
          
         $userInput = $request->input('one_time_password');
         if ($user !== null) {
        if(isset($user['token'])&& $userInput){
         
             $tfa = new TwoFactorAuth('GOOGLE_2FA');
            $totpCode = $tfa->getCode($user['token']);

            $isTotpValid = $tfa->verifyCode($user['token'], $userInput);
            if ($isTotpValid) {
                $user->totp_verified = true;
                $user->save(); 
             // return view('home');
             Session::regenerate();
             return redirect()->route('home');
                //return redirect()->route('authenticated.dashboard');
            } 
           
                return view('google2fa.index')->withErrors(['totp' => 'Invalid TOTP']);
            
        }
    }

        //
    }
}

