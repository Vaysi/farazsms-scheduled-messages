<?php

namespace App\Http\Middleware;

use Closure;

class ShouldPay
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
        if(auth()->check()){
            if(user()->should_pay){
                return redirect()->route('shouldPay');
            }else {
                return $next($request);
            }
        }else {
            return redirect()->route('login');
        }
    }
}
