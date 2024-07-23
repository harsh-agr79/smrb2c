<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function confirmcart(){
        $user = DB::table('customers')->where("id", session()->get('USER_ID'))->first();
        $cart = $user->cart;
        $break = explode(":", $cart);
        $products = explode(",", $break[0]);
        $qty = explode(",", $break[1]);
        $oid = $user->id.getNepaliDay(date('Y-m-d H:i:s')).getNepaliMonth(date('Y-m-d H:i:s')).getNepaliYear(date('Y-m-d H:i:s')).date("His");
        for ($i=0; $i < count($products); $i++) {
            if($qty[$i] > 0 && $qty[$i] != 'NaN'){
                $prod = DB::table('products')->where("id", $products[$i])->first();
                DB::table("orders")->insert([
                    'date'=>date('Y-m-d H:i:s'),
                    'order_id'=>$oid,
                    'name'=>$user->name,
                    'user_id'=>$user->id,
                    'item'=>$prod->name,
                    'product_id'=>$prod->id,
                    'brand'=>$prod->brand,
                    'brand_id'=>$prod->brand_id,
                    'category'=>$prod->category,
                    'category_id'=>$prod->category_id,
                    'net'=>$prod->net,
                    'price'=>$prod->price,
                    'quantity'=>$qty[$i],
                    'approvedquantity'=>"0",
                    'mainstatus'=>"blue",
                    'status'=>"pending",
                    'discount'=>"0",
                    'sdis'=>"0",
                    'nepday'=>getNepaliDay(date('Y-m-d H:i:s')),
                    'nepmonth'=>getNepaliMonth(date('Y-m-d H:i:s')),
                    'nepyear'=>getNepaliYear(date('Y-m-d H:i:s'))
                ]);
            }   
        }
        DB::table('customers')->where("id", session()->get('USER_ID'))->update([
            'cart'=>NULL
        ]);
        ldis($oid);
        return redirect("/user/home");
    }
    public function savecart(){
        $user = DB::table('customers')->where("id", session()->get('USER_ID'))->first();
        $cart = $user->cart;
        $break = explode(":", $cart);
        $products = explode(",", $break[0]);
        $qty = explode(",", $break[1]);
        $oid = $user->id.getNepaliDay(date('Y-m-d H:i:s')).getNepaliMonth(date('Y-m-d H:i:s')).getNepaliYear(date('Y-m-d H:i:s')).date("His");
        for ($i=0; $i < count($products); $i++) {
            if($qty[$i] > 0 && $qty[$i] != 'NaN'){
                $prod = DB::table('products')->where("id", $products[$i])->first();
                DB::table("orders")->insert([
                    'date'=>date('Y-m-d H:i:s'),
                    'order_id'=>$oid,
                    'name'=>$user->name,
                    'user_id'=>$user->id,
                    'item'=>$prod->name,
                    'product_id'=>$prod->id,
                    'brand'=>$prod->brand,
                    'brand_id'=>$prod->brand_id,
                    'category'=>$prod->category,
                    'category_id'=>$prod->category_id,
                    'net'=>$prod->net,
                    'price'=>$prod->price,
                    'quantity'=>$qty[$i],
                    'approvedquantity'=>"0",
                    'mainstatus'=>"blue",
                    'status'=>"pending",
                    'discount'=>"0",
                    'sdis'=>"0",
                    'marketer'=>$user->marketer,
                    'marketer_id'=>$user->marketer_id,
                    'nepday'=>getNepaliDay(date('Y-m-d H:i:s')),
                    'nepmonth'=>getNepaliMonth(date('Y-m-d H:i:s')),
                    'nepyear'=>getNepaliYear(date('Y-m-d H:i:s')),
                    'save'=>'save'
                ]);
            }   
        }
        DB::table('customers')->where("id", session()->get('USER_ID'))->update([
            'cart'=>NULL
        ]);
        ldis($oid);
        return redirect("/user/home");
    }

    public function oldorders(Request $request){
        $cust = DB::table('customers')->where('id', session()->get('USER_ID'))->first();
        $name = $cust->name;
        $query = DB::table('orders');
        $query = $query->where(['deleted_at'=>NULL, 'save'=>NULL, 'user_id'=>session()->get('USER_ID')])->orderBy('date', 'DESC')->groupBy('order_id');
        if($request->get('date')){
           $query = $query->whereDate('date', $request->get('date'));
           $result['date']= $request->get('date');
        }
        else{
            $result['date']= '';
        }
        $query = $query->paginate(10);
        $result['data']=$query;
        $result['page'] = 'All Orders';
    

        return view('customer/orders', $result);
    }
    public function savedorders(Request $request){
        $cust = DB::table('customers')->where('id', session()->get('USER_ID'))->first();
        $name = $cust->name;
        $query = DB::table('orders');
        $query = $query->where(['deleted_at'=>NULL, 'save'=>'save', 'user_id'=>session()->get('USER_ID')])->orderBy('date', 'DESC')->groupBy('order_id');
        if($request->get('date')){
           $query = $query->whereDate('date', $request->get('date'));
           $result['date']= $request->get('date');
        }
        else{
            $result['date']= '';
        }
        $query = $query->paginate(10);
        $result['data']=$query;
        $result['page'] = 'Saved Baskets';
    

        return view('customer/orders', $result);
    }
    public function detail(Request $request, $orderid){
        $result['data'] = DB::table('orders')->where('order_id', $orderid) 
        ->join('products', 'orders.product_id', '=', 'products.id')
        ->selectRaw('orders.*, products.stock')
        ->get();

        return view('customer/detail', $result);
    }
    public function detailedit(Request $request){
        $orderid = $request->post('order_id');
        DB::table('orders')->where('order_id', $orderid)->update([
            'userremarks'=>$request->post('userremarks')
        ]);
        $order = DB::table('orders')->where('order_id', $orderid)->first()->save;
        if($order == "save"){
            return redirect('user/savedorders');
        }
        else{
            return redirect('user/oldorders');
        }
    }
    public function recieveorder(Request $request, $id){
        $order = DB::table('orders')->where('order_id', $id)->get();
        if($order[0]->received == NULL){
            $val = 'on';
            $date = date('Y-m-d H:i:s');
        }
        else{
            $val = NULL;
            $date = NULL;
        }
        DB::table('orders')->where('order_id', $id)->update([
            'received'=>$val,
            'receiveddate'=>$date
        ]);
        $msg = 'success';
        return response()->json($msg,200);
    }
    public function confirm(Request $request, $orderid)
    {
        DB::table('orders')->where('order_id', $orderid)->update([
            'date'=>date('Y-m-d H:i:s'),
            'save'=>NULL
        ]);
        return redirect('user/oldorders');
    }
    public function editorder(Request $request, $orderid){
        $order = DB::table('orders')->where("order_id",$orderid)->first();
        if($order->mainstatus !== "blue"){
            return redirect("/");
        }
        else{
            $result[ 'brands' ] = DB::table( 'brands' )->get();
            $result[ 'category' ] = DB::table( 'categories' )->get();
            $result[ 'order' ] = DB::table( 'orders' )->where( 'order_id', $orderid )
            ->join( 'products', 'products.id', '=', 'orders.product_id' )
            ->selectRaw( 'orders.*, products.images, products.hide, products.stock, products.brand, products.brand_id, products.category, products.category_id' )
            ->get();
            $result[ 'data' ] = DB::table( 'products' )
            ->whereNotIn( 'name', DB::table( 'orders' )->where( 'order_id', $orderid )->pluck( 'item' )->toArray() )
           ->orderBy("ordernum", 'ASC')->get();
    
            return view( 'customer/editorder', $result );
        }  
    }
    public function editorder_process(Request $request){
        $orderid = $request->post( 'orderid' );
        $order = DB::table( 'orders' )->where( 'order_id', $orderid )->get();
        $user = DB::table( 'customers' )->where( 'id', session()->get("USER_ID") )->first();
        $products = $request->post( 'prodid', [] );
        $qty = $request->post( 'quantity', [] );
        $ids = $request->post( 'id', [] );
        $date = $request->post( 'date' );
        $disc = "0";
        $disc2 = "0";
        foreach ($order as $item) {
            if($item->discount > 0 || $item->sdis > 0){
                $disc = $item->discount;
                $disc2 = $item->sdis;
                break;
            }
        }
        for ( $i = 0; $i < count( $ids );
        $i++ ) {
            if ( $qty[ $i ] !== '0' && $qty[ $i ] !== NULL && $qty[ $i ] !== "" ) {
                if ( $ids[ $i ] === 'newitem' ) {
                    $prod = DB::table( 'products' )->where( 'id', $products[ $i ] )->first();
                    if($prod->net == 'on'){
                        $dis = "0";
                        $dis2 = "0";
                    }
                    else{
                        $dis = $disc;
                        $dis2 = $disc2;
                    }
                    DB::table( 'orders' )->insert( [
                        'date'=>$date.' '.date( 'H:i:s' ),
                        'order_id'=>$orderid,
                        'name'=>$user->name,
                        'user_id'=>$user->id,
                        'item'=>$prod->name,
                        'product_id'=>$prod->id,
                        'brand'=>$prod->brand,
                        'brand_id'=>$prod->brand_id,
                        'category'=>$prod->category,
                        'category_id'=>$prod->category_id,
                        'net'=>$prod->net,
                        'price'=>$prod->price,
                        'quantity'=>$qty[ $i ],
                        'approvedquantity'=>'0',
                        'mainstatus'=>'blue',
                        'status'=>'pending',
                        'discount'=>$dis,
                        'sdis'=>$dis2,
                        'nepday'=>getNepaliDay( $date ),
                        'nepmonth'=>getNepaliMonth( $date ),
                        'nepyear'=>getNepaliYear( $date ),
                        'clnstatus'=>$order[0]->clnstatus,
                        'delivered'=>$order[0]->delivered,
                        'received'=>$order[0]->received,
                        'receiveddate'=>$order[0]->receiveddate,
                        'seen'=>$order[0]->seen,
                        'seenby'=>$order[0]->seenby,
                        'marketer'=>$user->marketer,
                        'marketer_id'=>$user->marketer_id,
                        'remarks'=>$order[0]->remarks,
                        'userremarks'=>$order[0]->userremarks,
                        'cartoons'=>$order[0]->cartoons,
                        'transport'=>$order[0]->transport,
                        'refid'=>$order[0]->refid,
                        'save'=>$order[0]->save,
                    ] );
                } else {
                    $prod = DB::table( 'products' )->where( 'id', $products[ $i ] )->first();
                    if($prod->net == 'on'){
                        $dis = "0";
                        $dis2 = "0";
                    }
                    else{
                        $dis = $disc;
                        $dis2 = $disc2;
                    }
                    $o =  DB::table( 'orders' )->where( 'id', $ids[$i] )->first();
                    if($qty[$i] == $o->approvedquantity && $o->status == 'approved'){
                    }
                    elseif($qty[$i] == $o->quantity && $o->status == "pending"){
                    } 
                     elseif($o->status == "rejected"){
                    }
                    else{
                        DB::table( 'orders' )->where( 'id', $ids[ $i ] )->update( [
                            'date'=>$date.' '.date( 'H:i:s' ),
                            'name'=>$user->name,
                            'user_id'=>$user->id,
                            'item'=>$prod->name,
                            'product_id'=>$prod->id,
                            'brand'=>$prod->brand,
                            'brand_id'=>$prod->brand_id,
                            'category'=>$prod->category,
                            'category_id'=>$prod->category_id,
                            'net'=>$prod->net,
                            'price'=>$prod->price,
                            'quantity'=>$qty[ $i ],
                            'approvedquantity'=>'0',
                            'mainstatus'=>'blue',
                            'status'=>'pending',
                            'discount'=>$dis,
                            'sdis'=>$dis2,
                            'marketer'=>$user->marketer,
                            'marketer_id'=>$user->marketer_id,
                            'nepday'=>getNepaliDay( $date ),
                            'nepmonth'=>getNepaliMonth( $date ),
                            'nepyear'=>getNepaliYear( $date )
                        ] );
                    }   
                }
            }
            elseif ($qty[$i] == NULL || $qty[$i] == '0' || $qty[$i] == '' && $id[$i] !== NULL) {
                DB::table('orders')->where('id', $ids[$i])->delete();
            }
            
        }
        updateMainStatus($orderid);
        ldis($orderid);
        if($order[0]->save == "save"){
            return redirect('user/savedorders');
        }
        else{
            return redirect('user/oldorders');
        }
    }
    public function deleteorder(Request $request,$orderid){
        $order = DB::table('orders')->where("order_id",$orderid)->first();
        if($order->mainstatus !== "blue"){
            return redirect("/");
        }
        else{
            DB::table("orders")->where("order_id",$orderid)->delete();
            if($order->save == NULL){
                return redirect("/user/oldorders");
            }
            else{
            return redirect("/user/savedorders");
            }
        }
    }
}

