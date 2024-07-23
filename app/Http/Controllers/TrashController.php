<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TrashController extends Controller
{
    public function trash(){
        $result['orders'] = DB::table('orders')->whereNot('deleted_at', NULL)->groupBy('order_id')->orderBy('date', 'DESC')->get();
        $result['payments'] = DB::table('payments')->where('deleted', "on")->get();
        
        return view('admin/trash', $result);
    }
    public function order_restore($orderid){
        DB::table('orders')->where('order_id', $orderid)->update([
            'deleted_at'=>NULL
        ]);
        return redirect('/trash');
    }
    public function payment_restore($payid){
        DB::table('payments')->where('paymentid', $payid)->update([
            'deleted'=>NULL,
            'deleted_at'=>NULL
        ]);
        return redirect('/trash');
    }
    public function order_delete($orderid){
        DB::table('orders')->where('order_id', $orderid)->delete();
        return redirect('/trash');
    }
    public function payment_delete($payid){
        DB::table('payments')->where('paymentid', $payid)->delete();
        return redirect('/trash');
    }
}
