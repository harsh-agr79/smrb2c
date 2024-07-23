<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home(){
        $user = DB::table('customers')->where('id', session()->get("USER_ID"))->first();
        $result['prods'] = DB::table('products')->whereIn('brand_id', explode('|', $user->brands))->where("hide", NULL)->orderBy("ordernum", 'ASC')->get();
        $result['brands'] = DB::table('brands')->whereIn('id', explode('|', $user->brands))->get();
        $result['category'] = DB::table('categories')->get();
        return view('customer/home', $result);
    }
    public function homereal(){
        $result['data']=DB::table('front')->where('type', 'image')->get();
        $result['data2']=DB::table('front')->where('type', 'message')->get();

        
        $result['thirdays'] = DB::table('orders')
        ->where(['deleted_at'=>NULL,'net'=>NULL,'save'=>NULL])
        ->where('user_id', session()->get("USER_ID"))
        ->where('status','approved') 
        ->whereBetween('date', [now()->subDays(15), now()->addDays(1)])
        ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')->groupBy('name')
        ->get();

        $result['fourdays'] = DB::table('orders')
        ->where(['deleted_at'=>NULL, 'net'=>NULL])
        ->where('user_id', session()->get("USER_ID"))
        ->whereBetween('date', [now()->subDays(25), now()->addDays(1)])
        ->where('status','approved') 
        ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')->groupBy('name')
        ->get();

        $result['sixdays'] = DB::table('orders')
        ->where(['deleted_at'=>NULL, 'net'=>NULL])
        ->where('user_id', session()->get("USER_ID"))
        ->whereBetween('date', [now()->subDays(35), now()->addDays(1)])
        ->where('status','approved') 
        ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')->groupBy('name')
        ->get();
        $result['nindays'] = DB::table('orders')
        ->where(['deleted_at'=>NULL, 'net'=>NULL])
        ->where('user_id', session()->get("USER_ID"))
        ->whereBetween('date', [now()->subDays(45), now()->addDays(1)])
        ->where('status','approved') 
        ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')->groupBy('name')
        ->get();
        return view('customer/homereal', $result);
    }

    public function printcat(Request $request, $brands, $cats){
        $bs = explode("_", $brands);
        $cs = explode("_", $cats);
        $query = DB::table('products')->where('hide', NULL)->orderBy("ordernum", "ASC");
        if($bs[0] != "0"){
            $query = $query->whereIn("brand_id", $bs);
        }
        if($cs[0] != "0"){
            $query = $query->whereIn("category_id", $cs);
        }
        $query = $query->get();
        $result['data'] = $query;

        return view("customer/printcat", $result);
    }
}
