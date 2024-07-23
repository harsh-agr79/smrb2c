<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->session()->has('ADMIN_LOGIN')){
            if(session()->get('ADMIN_TYPE') == 'admin' || session()->get('ADMIN_TYPE') == 'superuser'){   
                view()->share('admin', DB::table('admins')->where('id', session()->get('ADMIN_ID'))->first());
            }
            else{
                $perms = explode("|",DB::table('staffs')->where('id', session()->get('STAFF_ID'))->first()->permission);
                $perms2 = ['dashboard', 'logout', 'findcustomer', 'finditem'];
                $uri =  $url = request()->route()->uri;
                if(in_array($uri, $perms) || in_array($uri, $perms2)){
                            view()->share('admin', DB::table('staffs')->where('id', session()->get('STAFF_ID'))->first());
                            view()->share('perms', explode("|",DB::table('staffs')->where('id', session()->get('STAFF_ID'))->first()->permission));
                 
                }  
                else{
                    $request->session()->flash('error','Access Denied');
                    return redirect('/');
                }
            }
        }
        else{
            $request->session()->flash('error','Access Denied');
            return redirect('/');
        }
        return $next($request);
    }
}
