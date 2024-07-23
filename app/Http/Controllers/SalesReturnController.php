<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SalesReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('salesreturns')->groupBy('returnid')->orderBy('date', 'DESC');
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
        $query = $query->get();
        $result['data'] = $query;
        return view('admin/slr',$result);
    }

    public function createslr(Request $request){
        $result['data'] = DB::table('products')->orderBy('ordernum', 'ASC')->get();
        $result[ 'brands' ] = DB::table( 'brands' )->get();
        $result[ 'category' ] = DB::table( 'categories' )->get();
        return view('admin/createslr', $result);
    }

    // public function addslr(Request $request){
    //     $user = DB::table( 'customers' )->where( 'name', $request->post( 'name' ) )->first();
    //     $products = $request->post( 'prodid', [] );
    //     $qty = $request->post( 'quantity', [] );
    //     $date = $request->post( 'date' );
    //     for ( $i = 0; $i < count( $products );
    //     $i++ ) {
    //         if ( $qty[ $i ] > 0 ) {
    //             $prod = DB::table( 'products' )->where( 'id', $products[ $i ] )->first();
    //             DB::table( 'salesreturns' )->insert( [
    //                 'date'=>$date,
    //                 'returnid'=>'slr'.$user->id.time(),
    //                 'name'=>$user->name,
    //                 'user_id'=>$user->id,
    //                 'item'=>$prod->name,
    //                 'product_id'=>$prod->id,
    //                 'brand'=>$prod->brand,
    //                 'brand_id'=>$prod->brand_id,
    //                 'category'=>$prod->category,
    //                 'category_id'=>$prod->category_id,
    //                 'price'=>$prod->price,
    //                 'quantity'=>$qty[ $i ],
    //                 'discount'=>'0',
    //                 'nepday'=>getNepaliDay( date( 'Y-m-d H:i:s' ) ),
    //                 'nepmonth'=>getNepaliMonth( date( 'Y-m-d H:i:s' ) ),
    //                 'nepyear'=>getNepaliYear( date( 'Y-m-d H:i:s' ) )
    //             ] );
    //         }
    //     }
    //     updatebalance($user->id);
    //     return redirect('slr');
    // }
    public function addslr( Request $request ) {
        $user = DB::table( 'customers' )->where( 'name', $request->post( 'name' ) )->first();
        $products = $request->post( 'prodid', [] );
        $qty = $request->post( 'quantity', [] );
        $date = $request->post( 'date' );
        $oid = "slr".$user->id.getNepaliDay( $date.' '.date( 'H:i:s' ) ).getNepaliMonth( $date.' '.date( 'H:i:s' ) ).getNepaliYear( $date.' '.date( 'H:i:s' ) ).date( 'His' );
        for ( $i = 0; $i < count( $products );
        $i++ ) {
            if ( $qty[ $i ] > 0 ) {
                $prod = DB::table( 'products' )->where( 'id', $products[ $i ] )->first();
                DB::table( 'salesreturns' )->insert( [
                    'date'=>$date.' '.date( 'H:i:s' ),
                    'returnid'=>$oid,
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
                    'discount'=>"0",
                    'sdis'=>"0",
                    'nepday'=>getNepaliDay( date( 'Y-m-d H:i:s' ) ),
                    'nepmonth'=>getNepaliMonth( date( 'Y-m-d H:i:s' ) ),
                    'nepyear'=>getNepaliYear( date( 'Y-m-d H:i:s' ) )
                ] );
            }

        }
        updatebalance($user->id);
        return redirect( '/slr' );
    }

    public function detail(Request $request, $returnid){
        $result['data'] = DB::table('salesreturns')->where('returnid', $returnid) 
        ->join('products', 'salesreturns.product_id', '=', 'products.id')
        ->selectRaw('salesreturns.*, products.stock')
        ->get();

        return view('admin/slrdetail', $result);
    }

    public function editslr(Request $request, $returnid){
        $result[ 'brands' ] = DB::table( 'brands' )->get();
        $result[ 'category' ] = DB::table( 'categories' )->get();
        $result['slr'] = DB::table('salesreturns')->where('returnid', $returnid)
        ->join('products', 'products.id', '=', 'salesreturns.product_id')
        ->selectRaw('salesreturns.*, products.images, products.hide, products.stock')
        ->get();
        $result['data'] = DB::table('products')
        ->whereNotIn('name', DB::table('salesreturns')->where('returnid', $returnid)->pluck('item')->toArray())
        ->orderBy('category', 'ASC')
        ->get();

        return view('admin/editslr', $result);
    }

    // public function editslr_process(Request $request)
    // {
    //     $returnid = $request->post( 'returnid' );
    //     $slr = DB::table( 'salesreturns' )->where( 'returnid', $returnid )->get();
    //     $user = DB::table( 'customers' )->where( 'name', $request->post( 'name' ) )->first();
    //     $products = $request->post( 'prodid', [] );
    //     $qty = $request->post( 'quantity', [] );
    //     $ids = $request->post( 'id', [] );
    //     $date = $request->post( 'date' );
    //     for ( $i = 0; $i < count( $ids );
    //     $i++ ) {
    //         if ( $qty[ $i ] !== '0' && $qty[ $i ] !== NULL && $qty[ $i ] !== "" ) {
    //             if ( $ids[ $i ] === 'newitem' ) {
    //                 $prod = DB::table( 'products' )->where( 'id', $products[ $i ] )->first();
    //                 DB::table( 'salesreturns' )->insert( [
    //                 'date'=>$date,
    //                 'returnid'=>$returnid,
    //                 'name'=>$user->name,
    //                 'user_id'=>$user->id,
    //                 'item'=>$prod->name,
    //                 'product_id'=>$prod->id,
    //                 'brand'=>$prod->brand,
    //                 'brand_id'=>$prod->brand_id,
    //                 'category'=>$prod->category,
    //                 'category_id'=>$prod->category_id,
    //                 'price'=>$prod->price,
    //                 'quantity'=>$qty[ $i ],
    //                 'discount'=>$slr[0]->discount,
    //                 'nepday'=>getNepaliDay( date( 'Y-m-d H:i:s' ) ),
    //                 'nepmonth'=>getNepaliMonth( date( 'Y-m-d H:i:s' ) ),
    //                 'nepyear'=>getNepaliYear( date( 'Y-m-d H:i:s' ) ),
    //                 'remarks'=>$slr[0]->remarks
    //                 ] );
    //             } else {
    //                 $prod = DB::table( 'products' )->where( 'id', $products[ $i ] )->first();
    //                 $o =  DB::table( 'salesreturns' )->where( 'id', $ids[$i] )->first();
    //                     DB::table( 'salesreturns' )->where( 'id', $ids[ $i ] )->update( [
    //                         'date'=>$date,
    //                         'returnid'=>$returnid,
    //                         'name'=>$user->name,
    //                         'user_id'=>$user->id,
    //                         'item'=>$prod->name,
    //                         'product_id'=>$prod->id,
    //                         'brand'=>$prod->brand,
    //                         'brand_id'=>$prod->brand_id,
    //                         'category'=>$prod->category,
    //                         'category_id'=>$prod->category_id,
    //                         'price'=>$prod->price,
    //                         'quantity'=>$qty[ $i ],
    //                         'discount'=>$slr[0]->discount,
    //                         'nepday'=>getNepaliDay( date( 'Y-m-d H:i:s' ) ),
    //                         'nepmonth'=>getNepaliMonth( date( 'Y-m-d H:i:s' ) ),
    //                         'nepyear'=>getNepaliYear( date( 'Y-m-d H:i:s' ) )
    //                     ] );
    //             }
    //         }
    //         elseif ($qty[$i] == NULL || $qty[$i] == '0' || $qty[$i] == '' && $id[$i] !== NULL) {
    //             DB::table('salesreturns')->where('id', $ids[$i])->delete();
    //         }
            
    //     }
    //     updatebalance($user->id);
    //     return redirect('slrdetail/'.$returnid);
    // }
    public function editslr_process( Request $request ) {
        // dd( $request->post() );
        $returnid = $request->post( 'returnid' );
        $slr = DB::table( 'salesreturns' )->where( 'returnid', $returnid )->get();
        $user = DB::table( 'customers' )->where( 'name', $request->post( 'name' ) )->first();
        $products = $request->post( 'prodid', [] );
        $qty = $request->post( 'quantity', [] );
        $ids = $request->post( 'id', [] );
        $date = $request->post( 'date' );
        $disc = "0";
        $disc2 = "0";
        foreach ($slr as $item) {
            if($item->discount > 0 || $item->sdis > 0){
                $disc = $item->discount;
                $disc2 = $item->sdis;
                break;
            }
        }
        DB::table( 'salesreturns' )->where('returnid',$returnid)->update( [
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
                    DB::table( 'salesreturns' )->insert( [
                        'date'=>$date.' '.date( 'H:i:s' ),
                        'returnid'=>$returnid,
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
                        'discount'=>$dis,
                        'sdis'=>$dis2,
                        'nepday'=>getNepaliDay( $date ),
                        'nepmonth'=>getNepaliMonth( $date ),
                        'nepyear'=>getNepaliYear( $date ),
                        'remarks'=>$slr[0]->remarks,
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
                    $o =  DB::table( 'salesreturns' )->where( 'id', $ids[$i] )->first();
                        DB::table( 'salesreturns' )->where( 'id', $ids[ $i ] )->update( [
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
                            'discount'=>$dis,
                            'sdis'=>$dis2,
                            'nepday'=>getNepaliDay( $date ),
                            'nepmonth'=>getNepaliMonth( $date ),
                            'nepyear'=>getNepaliYear( $date ),
                            'remarks'=>$slr[0]->remarks,
                        ] );  
                }
            }
            elseif ($qty[$i] == NULL || $qty[$i] == '0' || $qty[$i] == '' && $id[$i] !== NULL) {
                DB::table('salesreturns')->where('id', $ids[$i])->delete();
            }
            
        }

        updatebalance($user->id);
        return redirect('slrdetail/'.$returnid);
    }
    public function editslrdet_process(Request $request){
        $returnid = $request->post('returnid');
        $id = $request->post('id', []);
        $price = $request->post('price', []);
        $discount=$request->post('discount');
        $discount2=$request->post('sdis');
        // dd($request->post());
        for ($i=0; $i < count($id); $i++) { 
            $prod = DB::table("products")->where('id',DB::table( 'salesreturns' )->where( 'id', $id[ $i ] )->first()->product_id)->first()->net;
            if($prod == "on"){
                $dis = 0;
                $dis2 = 0;
            }
            else{
                $dis = $discount;
                $dis2 = $discount2;
            }
            if($dis == NULL || $dis == ""){
                $dis = 0;
            }
            if($dis2 == NULL || $dis2 == ""){
                $dis2 = 0;
            }
            DB::table('salesreturns')->where('returnid', $returnid)->where('id', $id[$i])->update([
                'price'=>$price[$i],
                'discount'=>$dis,
                'sdis'=>$dis2,
                'remarks'=>$request->post('remarks'),
            ]);
        }
        $userid = DB::table('salesreturns')->where('returnid', $returnid)->first()->user_id;
        updatebalance($userid);
        return redirect('slrdetail/'.$returnid);
    }
    public function deleteslr(Request $request, $id){
        $userid = DB::table('salesreturns')->where('returnid', $id)->first()->user_id;
        DB::table('salesreturns')->where('returnid', $id)->delete();
        updatebalance($userid);
        return redirect('/slr');
    }

}
