<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Http\Middleware\CheckAdminRole; // Replace with your middleware class

use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * 
     */
    
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request); 
        }
    
        // User is not admin, redirect or deny access
        //return redirect()->route('home')->with('error', 'You are not authorized.');
        return response()->json(['error', 'You are not authorized.'], 401);
    }
}
