<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class MarketerViewController extends Controller
{


    public function index(Request $request){
        $query = DB::table('payments')->where('deleted', NULL)->orderBy('date', 'DESC');
        $name = DB::table('marketers')->where('id', session()->get('MARKETER_ID'))->first()->userid;
        $query = $query->where('entry_by', $name);
        $result['date'] = '';
        $result['date2'] =  '';
        $result['name'] =  '';
        if($request->get('date')){
            $query = $query->where('date', '>=', $request->get('date'));
            $result['date'] =  $request->get('date');
        }
        if($request->get('date2')){
            $query = $query->where('date', '<=', $request->get('date2'));
            $result['date2'] =  $request->get('date2');
        }
        if($request->get('name')){
            $query = $query->where('name', $request->get('name'));
            $result['name'] =  $request->get('name');
        }
        $query = $query->paginate(100);
        $result['data'] = $query;
        return view('marketer/payment', $result);
    }
    public function addpay(Request $request, $id = ''){
        if($id !== ""){
            $pay = DB::table('payments')->where('paymentid', $id)->first();
            $result['date'] = $pay->date;
            $result['name'] = $pay->name;
            $result['type'] = $pay->type;
            $result['amount'] = $pay->amount;
            $result['voucher'] = $pay->voucher;
            $result['remarks'] = $pay->remarks;
            $result['payid'] = $pay->paymentid;
        }
        else{
            $result['date'] = date('Y-m-d H:i:s');
            $result['name'] = '';
            $result['type'] = '';
            $result['amount'] = '';
            $result['voucher'] = '';
            $result['remarks'] = '';
            $result['payid'] = '';
        }
        return view('marketer/addpayment', $result);
    }
    public function addpay_process(Request $request){
        $payid = $request->post('payid');
        $admin = DB::table('marketers')->find($request->session()->get('MARKETER_ID'));
        if($payid === NULL){
            DB::table('payments')->insert([
                'date'=>$request->post('date'),
                'name'=>$request->post('name'),
                'user_id'=>DB::table('customers')->where('name', $request->post('name'))->first()->id,
                'type'=>$request->post('type'),
                'paymentid'=>"pay".date('ymdhis'),
                'amount'=>$request->post('amount'),
                'voucher'=>$request->post('voucher'),
                'remarks'=>$request->post('remarks'),
                'entry_by'=>$admin->userid,
            ]);
            updatebalance(DB::table('customers')->where('name', $request->post('name'))->first()->id);
            return redirect('marketer/addpayment');
        }
        else{
            DB::table('payments')->where('paymentid', $payid)->update([
                'date'=>$request->post('date'),
                'name'=>$request->post('name'),
                'type'=>$request->post('type'),
                'amount'=>$request->post('amount'),
                'voucher'=>$request->post('voucher'),
                'remarks'=>$request->post('remarks'),
            ]);
            updatebalance(DB::table('customers')->where('name', $request->post('name'))->first()->id);
            return redirect('marketer/payments');
        }
    }
    public function deletepay(Request $request, $id){
        $userid = DB::table('payments')->where('paymentid', $id)->first()->user_id;
        DB::table('payments')->where('paymentid', $id)->update([
            'deleted'=>'on',
            'deleted_at'=>date('Y-m-d H:i:s')
        ]);
        updatebalance($userid);
        return redirect('marketer/payments');
    }


    public function getcustomer(){
        $c = DB::table('customers')->where("marketer_id", session()->get("MARKETER_ID"))->get();
        return response()->json($c);
    }


    public function statement(Request $request){
        $result['data'] = DB::table('customers')->where("marketer_id", session()->get("MARKETER_ID"))->orderBy('name', 'ASC')->get();
        return view('marketer/statement', $result);
    }
    public function balancesheet(Request $request, $id){

        $cust = DB::table('customers')->where('id', $id)->where("marketer_id", session()->get("MARKETER_ID"))->first();
        $result['cus'] = $cust;
        $today = date('Y-m-d');
        
            if($request->get('date') && $request->get('date2'))
            {
                $date = $request->get('date');
                $date2 = $request->get('date2');
            }
            elseif($request->get('date')){
                $date = $request->get('date');
                $date3 = date('Y-09-17');
                $date2 = date('Y-m-d', strtotime($date3. ' + 1 year -1 day'));
            }
            elseif($request->get('date2')){
                $date2 = $request->get('date2');
                $date = date('Y-09-17');
            }
            elseif($request->get('clear')){
               if(date('Y-m-d') < date('Y-09-17') ){
                $date2 = date('Y-09-16');  
                $date = date('Y-m-d', strtotime($date2. ' -1 year +1 day'));
               }
               else{
                   $date = date('Y-09-17');
                   $date2 = date('Y-m-d', strtotime($date. ' + 1 year -1 day'));
               }
            }
            else{
                if(date('Y-m-d') < date('Y-09-17') ){
                $date2 = date('Y-09-16');  
                $date = date('Y-m-d', strtotime($date2. ' -1 year +1 day'));
               }
               else{
                   $date = date('Y-09-17');
                   $date2 = date('Y-m-d', strtotime($date. ' + 1 year -1 day'));
               }
            }
        
        $result['date'] = $date;
        $result['date2'] = $date2;
        $date2 = date('Y-m-d', strtotime($date2. ' +1 day'));

        $result['oldorders'] = DB::table('orders')
        ->where('date', '<', $date)
        ->where('user_id',$id)
        ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('name')->where('status','approved') 
        ->get();

        $result['oldpayments'] = DB::table('payments')
        ->where(['deleted'=>NULL])
        ->where('date', '<', $date)
        ->where('user_id',$id)
        ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
        ->get();

        $result['oldslr'] = DB::table('salesreturns')
           ->where('user_id', $id)
           ->where('date', '<', $date)
           ->selectRaw('*, SUM(quantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('name') 
           ->get();
           
       $result['oldexp'] = DB::table('expenses')
           ->where('user_id', $id)
           ->where('date', '<', $date)
           ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
           ->get();

           $result['cuorsum'] = DB::table('orders')
           ->where(['save'=>NULL])
           ->where('user_id', $id)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('name')->where('status','approved') 
           ->get();

           $result['cupysum'] = DB::table('payments')
           ->where('deleted',NULL)
           ->where('user_id', $id)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
           ->get();

           $result['cuslrsum'] = DB::table('salesreturns')
           ->where('user_id', $id)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(quantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('name') 
           ->get();
           
            $result['cuexsum'] = DB::table('expenses')
           ->where('user_id', $id)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
           ->get();

        $orders = DB::table('orders')
        ->where(['save'=>null])
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->where('status','approved')
        ->where('user_id',$id)
        ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('order_id') 
        ->orderBy('orders.date','desc')
        ->get();

        $payments = DB::table('payments')->where('user_id', $id)
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->where('deleted',NULL)->get();

        $slrs = DB::table('salesreturns')
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->selectRaw('*, SUM(quantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('returnid')->where('user_id',$id) 
        ->orderBy('date','desc')
        ->get();
        
        $exp = DB::table('expenses')
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->selectRaw('*, SUM(amount) as sum')->where('user_id', $id)
        ->orderBy('date', 'desc')
        ->get();

        $data = array();
        foreach($orders as $item){
            if($item->name == NULL){

            }
            else{
            $data[] = [
                'name'=>$item->name,
                'created'=>$item->date,
                'ent_id'=>$item->order_id,
                'debit'=>$item->sum,
                'nar'=>$item->remarks,
                'vou'=>'',
                'credit'=>'0',
                'type'=>'sale',
            ];}
        }
        foreach($payments as $item){
            if($item->name == NULL){

            }
            else{
            $data[] = [
                'name'=>$item->name,
                'created'=>$item->date,
                'ent_id'=>$item->paymentid,
                'id'=>$item->id,
                'debit'=>'0',
                'nar'=>'',
                'vou'=>$item->voucher,
                'credit'=>$item->amount,
                'type'=>$item->type,
            ];}
        }
        foreach($slrs as $item){
            if($item->name == NULL){

            }
            else{
            $data[] = [
                'name'=>$item->name,
                'created'=>$item->date,
                'ent_id'=>$item->returnid,
                'debit'=>'0',
                'nar'=>'',
                'vou'=>'',
                'credit'=>$item->sum,
                'type'=>'Sales Return',
            ];}
        }
        foreach($exp as $item){
            if($item->name == NULL){

            }
            else{
                $data[] = [
                    'name'=>$item->name,
                    'created'=>$item->date,
                    'ent_id'=>$item->expenseid,
                    'id'=>$item->id,
                    'debit'=>$item->amount,
                    'nar'=>'',
                    'vou'=>$item->particular,
                    'credit'=>'0',
                    'type'=>'expense',
                ];
            }   
        }
            usort($data, function($a, $b) {
                return strtotime($a['created']) - strtotime($b['created']);
            });

        $result['data'] = collect($data);
        return view('marketer/balancesheet', $result);
    }

    public function dashboard( Request $request ) {
        $result[ 'date' ] = '';
        $result[ 'date2' ] = '';
        $result[ 'status' ] = '';
        $result[ 'product' ] = '';
        $result[ 'name' ] = '';

        $query = DB::table( 'orders' );
        $query = $query->where( [ 'deleted_at'=>NULL, 'save'=>NULL ] )->orderBy( 'date', 'DESC' )->where('user_id', DB::table('customers')->where('marketer_id', session()->get('MARKETER_ID'))->pluck('id')->toArray());
        if ( $request->get( 'name' ) ) {
            $query = $query->where( 'name', $request->get( 'name' ) )->groupBy( 'order_id' );
            $result[ 'name' ] = $request->get( 'name' );
        } else {
            $result[ 'name' ] = '';
        }
        if ( $request->get( 'date' ) ) {
            $query = $query->where( 'date', '>=', $request->get( 'date' ) )->groupBy( 'order_id' );
            $result[ 'date' ] = $request->get( 'date' );
        }
        if ( $request->get( 'date2' ) ) {
            $query = $query->where( 'date', '<=', $request->get( 'date2' ) )->groupBy( 'order_id' );
            $result[ 'date2' ] = $request->get( 'date2' );
        }
        if ( $request->get( 'status' ) && $request->get( 'product' ) == '' ) {
            $query = $query->where( 'status', $request->get( 'status' ) )->groupBy( 'order_id' );
            $result[ 'status' ] = $request->get( 'status' );
        }
        if ( $request->get( 'status' ) == '' && $request->get( 'product' ) != '' ) {
            $query = $query->where( 'item', $request->get( 'product' ) );
            $result[ 'product' ] = $request->get( 'product' );
        }
        if ( $request->get( 'status' ) && $request->get( 'product' ) != '' ) {
            $query = $query->where( 'status', $request->get( 'status' ) );
            $query = $query->where( 'item', $request->get( 'product' ) );
            $result[ 'status' ] = $request->get( 'status' );
            $result[ 'product' ] = $request->get( 'product' );
        } else {
            $query = $query->groupBy( 'order_id' );
        }
        $query = $query->paginate( 50 );
        $result[ 'data' ] = $query;

        return view( 'marketer/dashboard', $result );
    }

    public function details( Request $request, $orderid ) {
        $result[ 'data' ] = DB::table( 'orders' )->where( 'order_id', $orderid )
        ->join( 'products', 'orders.product_id', '=', 'products.id' )
        ->selectRaw( 'orders.*, products.stock' )
        ->get();

        return view( 'marketer/orderdetail', $result );
    }

    public function addorder( Request $request ) {
        $result[ 'brands' ] = DB::table( 'brands' )->get();
        $result[ 'category' ] = DB::table( 'categories' )->get();
        $result[ 'data' ] = DB::table( 'products' )->orderBy("ordernum", 'ASC')->where('hide', NULL)->get();
        return view( 'marketer.addorder', $result );
    }

    public function createorder( Request $request ) {
        $user = DB::table( 'customers' )->where( 'name', $request->post( 'name' ) )->first();
        $products = $request->post( 'prodid', [] );
        $qty = $request->post( 'quantity', [] );
        $date = $request->post( 'date' );
        $oid = $user->id.getNepaliDay( $date.' '.date( 'H:i:s' ) ).getNepaliMonth( $date.' '.date( 'H:i:s' ) ).getNepaliYear( $date.' '.date( 'H:i:s' ) ).date( 'His' );
        for ( $i = 0; $i < count( $products );
        $i++ ) {
            if ( $qty[ $i ] > 0 ) {
                $prod = DB::table( 'products' )->where( 'id', $products[ $i ] )->first();
                if($prod->net == 'on'){
                    $dis = "0";
                }
                else{
                    $dis = "5";
                }
                DB::table( 'orders' )->insert( [
                    'date'=>$date.' '.date( 'H:i:s' ),
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
                    'quantity'=>$qty[ $i ],
                    'approvedquantity'=>'0',
                    'mainstatus'=>'blue',
                    'status'=>'pending',
                    'discount'=>"0",
                    'sdis'=>"0",
                    'marketer'=>$user->marketer,
                    'marketer_id'=>$user->marketer_id,
                    'nepday'=>getNepaliDay( date( 'Y-m-d H:i:s' ) ),
                    'nepmonth'=>getNepaliMonth( date( 'Y-m-d H:i:s' ) ),
                    'nepyear'=>getNepaliYear( date( 'Y-m-d H:i:s' ) )
                ] );
            }

        }
        ldis($oid);
        return redirect( '/' );
    }

    public function editorder( Request $request, $orderid ) {
        $result[ 'brands' ] = DB::table( 'brands' )->get();
        $result[ 'category' ] = DB::table( 'categories' )->get();
        $result[ 'order' ] = DB::table( 'orders' )->where( 'order_id', $orderid )
        ->join( 'products', 'products.id', '=', 'orders.product_id' )
        ->selectRaw( 'orders.*, products.images, products.hide, products.stock, products.brand, products.brand_id, products.category, products.category_id' )
        ->get();
        $result[ 'data' ] = DB::table( 'products' )
        ->whereNotIn( 'name', DB::table( 'orders' )->where( 'order_id', $orderid )->pluck( 'item' )->toArray() )
        ->orderBy("ordernum", 'ASC')->get();

        return view( 'marketer/editorder', $result );
    }

    public function editorder_process( Request $request ) {
        // dd( $request->post() );
        $orderid = $request->post( 'orderid' );
        $order = DB::table( 'orders' )->where( 'order_id', $orderid )->get();
        $user = DB::table( 'customers' )->where( 'name', $request->post( 'name' ) )->first();
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
        DB::table( 'orders' )->where('order_id',$orderid)->update( [
            'date'=>$date.' '.date( 'H:i:s' ),
            'nepday'=>getNepaliDay( $date ),
            'nepmonth'=>getNepaliMonth( $date ),
            'nepyear'=>getNepaliYear( $date ),
        ]);
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
        updatebalance($user->id);
        ldis($orderid);
        return redirect("/");
    }
    public function deleteorder(Request $request,$orderid){
        $order = DB::table('orders')->where("order_id",$orderid)->first();
        if($order->mainstatus !== "blue"){
            return redirect("/");
        }
        else{
            DB::table("orders")->where("order_id",$orderid)->delete();
            updatebalance($order->user_id);
            return redirect("/");
        }
    }
}
