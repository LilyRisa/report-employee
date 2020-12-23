<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\Requestapi;

class Systemconfig
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
        $respon = new Requestapi('/api/v1/config');
        try{
            $data = $respon->methodGet(null);
        }catch(\GuzzleHttp\Exception\BadResponseException $e){
            $data = null;
        }
        if($data != null){
            return $next($request);
        }else{
            return redirect()->route('set_config');
        }
        
    }
}
