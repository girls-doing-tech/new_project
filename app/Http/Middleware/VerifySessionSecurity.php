<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class VerifySessionSecurity
{
    
    public function handle(Request $request, Closure $next): Response
    {
        $sessionUserAgent = Session::get('user_agent');
        $sessionIpAddress = Session::get('ip_address');
        $currentUserAgent = $request->header('User-Agent');
        $currentIpAddress = $request->ip();

        if ($sessionUserAgent !== $currentUserAgent || $sessionIpAddress !== $currentIpAddress) {
           // session_destroy();
            // Redirect to the login page
            //header("Location: login.php");
           // Session::invalidate();
           
           Auth::guard('web')->logout();
        } 
        return $next($request);
    }
}
