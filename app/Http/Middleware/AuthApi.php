<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;

class AuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->session()->get('username') == 'admin' && $request->session()->get('password') == 'admin'){
            return $next($request);
        }else{
            return \redirect()->route('login');
        }
        
    }
}
