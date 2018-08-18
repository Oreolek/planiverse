<?php

namespace App\Http\Middleware;

use Closure;

class CheckSession
{
    /**
     * Check the user's session to ensure their user object is loaded.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!session()->has('user'))
        {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
