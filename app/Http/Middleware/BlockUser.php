<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Session;

class BlockUser
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
        $response = $next($request);
        if(Auth::check() && Auth::user()->status == 2){
            $request->session()->flash('failed', 'Your Account is suspended');
            Auth::logout();
            return redirect()->route('login');
        }
        return $response;
    }
}
