<?php

namespace App\Http\Middleware;

use Closure;

class SteamLogin
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
        if(isset($_SESSION['steamid']))
            return $next($request);
        //else login page
        return redirect()->guest('/gologin');
    }
}
