<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class RegenerateSession
{
    public function handle($request, Closure $next)
    {
        $interval = 10; // 10 minutes (adjust the time as needed)
echo !Auth::check();
        if (!Auth::check()) {
            // User is not authenticated (not logged in)
            if (!session('initial_session_id')) {
                // First-time user or session cleared
                session()->put('initial_session_id', session()->getId());
            }
        } else {
            // User is authenticated (logged in)
            if (session('initial_session_id')) {
                // User just logged in, regenerate the session ID
               // session()->regenerate(true);
               // session()->forget('initial_session_id');
            }
        }

        if (time() - session('last_activity') >= $interval * 60) {
            // Session inactive for the specified interval, regenerate the session ID
            $oldSessionID = session()->getId();
            session()->regenerate(true);
            session(['last_activity' => time()]);
            // Log out the user (if needed) and perform any other cleanup
            // Auth::logout(); // Uncomment this line if you want to log out the user on session inactivity
        } else {
            // Update the last_activity timestamp for active sessions
            session(['last_activity' => time()]);
        }
        return $next($request);
    }
}

