<?php

namespace App\Http\Middleware;

use Closure;

class ShouldVerify
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
            if(!user()->phone_verified_at){
                alert()->error('خطا !','برای استفاده از سایت باید شماره خود را تایید کنید !');
                return redirect()->route('settings');
            }else {
                return $next($request);
            }
        }else {
            return redirect()->route('login');
        }
    }
}
