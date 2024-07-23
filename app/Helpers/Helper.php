<?php

function getqty( $i, $products, $qty ) {
    $a = array_search( $i, $products );
    $b = $qty[ $a ];
    if($b == "NaN" || $b == "0"){
        $b = "";
    }
    return $b;
}

function getTotalAmount( $orderid ) {
    $orders = DB::table( 'orders' )->where( 'order_id', $orderid )->get();
    $ts = 0;
    foreach ( $orders as $item ) {
        if ( $item->status == 'pending' ) {
            $ts = $ts + ( $item->quantity * $item->price *( 1 - 0.01*$item->discount))*(1-0.01*$item->sdis);
        } else {
            $ts = $ts + ( $item->approvedquantity * $item->price *( 1 - 0.01*$item->discount))*(1-0.01*$item->sdis);
        }
    }
    // $tsd = $ts - ( $ts * 0.01 * $orders[ 0 ]->discount );
    return $ts;
}
function ldis( $orderid ) {
    // $orders = DB::table( 'orders' )->where( 'order_id', $orderid )->get();
    // $ts = 0;
    // foreach ( $orders as $item ) {
    //     if ( $item->status == 'pending' ) {
    //         $ts = $ts + ( $item->quantity * $item->price * (1-($item->discount/100)));
    //     } else {
    //         $ts = $ts + ( $item->approvedquantity * $item->price );
    //     }
    // }
    // $tsd = $ts;
    // if($tsd >=100000){
    //     foreach($orders as $item){
    //         $prod = DB::table("products")->where("id", $item->product_id)->first()->net;
    //         if($prod != "on"){
    //             DB::table('orders')->where( 'id', $item->id )->update([
    //                 'ldis'=>"5"
    //             ]);
    //         }
    //         else{
    //             DB::table('orders')->where( 'id', $item->id )->update([
    //                 'ldis'=>"0"
    //             ]);
    //         }
    //     }
        
    // }   
    // else{
    //     DB::table('orders')->where( 'order_id', $orderid )->update([
    //         'ldis'=>"0"
    //     ]);
    // }
    // return $tsd;
}

function updateMainStatus( $orderid ) {
    $order = DB::table( 'orders' )->where( 'order_id', $orderid )->get();
    $tc = count( $order );
    $cc = 0;
    $rc = 0;
    foreach ( $order as $item ) {
        if ( $item->status == 'approved' ) {
            $cc = $cc + 1;
        } elseif ( $item->status == 'rejected' ) {
            $cc = $cc + 1;
            $rc = $rc + 1;
        }
    }
    if ( $order[ 0 ]->delivered == 'on' && $tc !== $rc && $tc == $cc) {
        $result = 'green';
        $del = "on";
        $cln = "delivered";
    } elseif ( $order[ 0 ]->clnstatus == 'packorder' && $tc !== $rc && $tc == $cc) {
        $result = 'deep-purple';
        $del = NULL;
        $cln = "packorder";
    } elseif ( $tc == $cc && $tc == $rc ) {
        $result = 'red';
        $del = NULL;
        $cln = NULL;
    } elseif ( $tc == $cc ) {
        $result = 'amber darken-1';
        $del = NULL;
        $cln = NULL;
    } else {
        $result = 'blue';
        $del = NULL;
        $cln = NULL;
    }
    DB::table( 'orders' )->where( 'order_id', $orderid )->update( [
        'mainstatus'=>$result,
        'delivered'=>$del,
        'clnstatus'=>$cln
    ] );
    updatebalance($order[0]->user_id);
}
function money($money){
    $money = round($money, 3);
    $decimal = (string)($money - floor($money));
    $money = floor($money);
    $length = strlen($money);
    $m = '';
    $money = strrev($money);
    for($i=0;$i<$length;$i++){
        if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$length){
            $m .=',';
        }
        $m .=$money[$i];
    }
    $result = strrev($m);
    $decimal = preg_replace("/0\./i", ".", $decimal);
    $decimal = substr($decimal, 0, 3);
    if( $decimal != '0'){
    $result = $result.$decimal;
    }
    return $result;
}
function updatebalance($id)
{
    $payment = DB::table( 'payments' )
    ->where( 'deleted', null )
    ->where( 'user_id', $id )
    ->selectRaw( '*, SUM(amount) as sum' )
    ->groupBy( 'user_id' )
    ->first();
    $order = DB::table( 'orders' )
    ->where(['deleted_at' => null, 'status' => 'approved', 'save' => null, 'user_id' => $id])
    ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')
    ->where( 'status', 'approved' )
    ->groupBy( 'user_id' )
    ->first();
    $slr = DB::table( 'salesreturns' )
    ->where( 'user_id', $id )
    ->selectRaw('*, SUM(quantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')
    ->groupBy( 'user_id' )
    ->first();
    $exp = DB::table( 'expenses' )
    ->where( 'user_id', $id )
    ->selectRaw( '*, SUM(amount) as sum' )
    ->groupBy( 'user_id' )
    ->first();
    $cus = DB::table( 'customers' )->where( 'id', $id )->first();

    $od = 0;
    $oc = 0;

    if ( $order != NULL ) {
        $or = $order->sum;
    } else {
        $or = 0;
    }
    if ( $exp != NULL ) {
        $ex = $exp->sum;
    } else {
        $ex = 0;
    }
    if ( $slr != NULL ) {
        $sr = $slr->sum;
    } else {
        $sr = 0;
    }
    if ( $payment != NULL ) {
        $py = $payment->sum;
    } else {
        $py = 0;
    }

    $tdb = $od+$or+$ex;
    $tcb = $oc+$py+$sr;

    if ( $tdb > $tcb ) {
        $result = array( 'red', round($tdb-$tcb, 3) );
        // return $result;
    } elseif ( $tdb < $tcb ) {
        $result = array( 'green', round($tcb-$tdb, 3) );
        // return $result;
    } else {
        $result = array( 'green', 0 );
        // return $result;
    }
    DB::table( 'customers' )->where( 'id', $id )->update( [
        'balance'=>implode( '|', $result )
    ] );
}

function shopname($id){
    $shop = DB::table('customers')->where('id', $id)->first()->shopname;

    return "(".$shop.")";
}