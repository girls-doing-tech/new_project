<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TOTPVerificationMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if the user has completed TOTP verification
            echo $user->totp_verified;
           
            if ($user->totp_verified) {
                // User has completed TOTP verification, allow access to the home page
                //return redirect()->route('home');
                return $next($request);
            }else   return response()->view('google2fa.index');
        }else  return response()->view('auth.login');

        // If not authenticated or TOTP not verified, stay on the same page
       
    }
        

        // If not authenticated, redirect to the login page
        //return redirect()->route('login');
    }


