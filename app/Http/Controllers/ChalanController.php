<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ChalanController extends Controller
{
    public function updatechalan(Request $request){
        $orderid = $request->post('order_id');
        $packorder = $request->post('packorder');

        DB::table('orders')->where('order_id', $orderid)->update([
            'clnstatus'=>$packorder,
        ]);
        updateMainStatus($orderid);
        return response()->json($request->all());
    }
    public function chalan(Request $request){
        $result['data'] = DB::table('orders')
            ->where('mainstatus', 'deep-purple')
            ->orderBy('date', 'DESC')
            ->groupBy('order_id')
            ->get();

        return view('admin/chalan', $result);
    }
    public function chalandetail(Request $request,$orderid){
        $result['data'] = DB::table('orders')->where('order_id', $orderid)->where('status', 'approved')->get();

        return view('admin/chalandetail', $result);
    }
}
