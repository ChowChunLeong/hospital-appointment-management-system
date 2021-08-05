<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NurseAuthCheck
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
        
        if(!session()->has('NurseLoggedUser') && $request->path() !='nurse/login'){
            return redirect('/nurse')->with('fail','Please login first');
        }
        
        if(session()->has('NurseLoggedUser') && $request->path() == '/nurse/login' ){
            return redirect('/nurse/dashbroad');
        }
        return $next($request)->header('Cache-Control','no-cache, no-store, max-age=0, must-revalidate')
                            ->header('Pragma','no-cache');
                        }
}
