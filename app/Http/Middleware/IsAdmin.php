<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->role == 'admin') {
             return $next($request);
        }

        return redirect('home')->with('error','You have not admin access');
      // It will redirect user back to home screen if they do not have is_admin=1 assigned in database
    }
}