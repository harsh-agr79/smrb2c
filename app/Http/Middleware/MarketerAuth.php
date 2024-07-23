<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class MarketerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->session()->has('MARKETER_LOGIN')){
            view()->share('marketer', DB::table('marketers')->where('id', session()->get('MARKETER_ID'))->first());
        }
        else{
            $request->session()->flash('error','Access Denied');
            return redirect('/');
        }
        return $next($request);
    }
}
