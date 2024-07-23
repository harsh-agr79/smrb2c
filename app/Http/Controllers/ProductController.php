<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Image;

class ProductController extends Controller {
    public function getproduct() {
        $c = DB::table( 'products' )->get();
        return response()->json( $c );
    }

    public function getproductdetail( Request $request, $id ) {
        $c = DB::table( 'products' )->where( 'id', $id )->first();
        return response()->json( $c );
    }

    public function products() {
        $result[ 'data' ] = Product::orderBy('ordernum', 'ASC')->get();
        return view( 'admin.products', $result );
    }

    public function addproduct() {
        $result[ 'brands' ] = DB::table( 'brands' )->get();
        $result[ 'category' ] = DB::table( 'categories' )->get();
        return view( 'admin/addproduct', $result );
    }

    public function addprod_process( Request $request ) {
        $request->validate( [
            'name'=>'required|unique:products,name,'.$request->post( 'id' ),
        ] );
        $image = array();
        if ( $files = $request->file( 'images' ) ) {
            $a = 0;
            $b = '';
            foreach ( $files as $file ) {
                $a = $a + 1;
                $ext = $file->getClientOriginalExtension();
                $image_name = time().$a.'prod'.'.'.$ext;
                $image_resize = Image::make( $file->getRealPath() );
                // $image_resize->fit( 300 );
                $image_resize->save( 'product/'.$image_name );
                array_push( $image, 'product/'.$image_name );
            }
        }

        DB::table( 'products' )->insert( [
            'name'=>$request->post( 'name' ),
            'category_id'=>$request->post( 'category_id' ),
            'category'=>DB::table( 'categories' )->where( 'id', $request->post( 'category_id' ) )->first()->category,
            'brand_id'=>$request->post( 'brand_id' ),
            'brand'=>DB::table( 'brands' )->where( 'id', $request->post( 'brand_id' ) )->first()->name,
            'stock'=>$request->post( 'stock' ),
            'hide'=>$request->post( 'hide' ),
            'price'=>$request->post( 'price' ),
            'offer'=>$request->post( 'offer' ),
            'featured'=>$request->post( 'featured' ),
            'details'=>$request->post( 'details' ),
            'net'=>$request->post('net'),
            'images'=>implode( '|', $image ),
            'ordernum'=>100000
        ] );
        return redirect( '/products' );
    }

    public function editproduct( $id ) {
        $prod = DB::table( 'products' )->where( 'id', $id )->first();
        $result[ 'brands' ] = DB::table( 'brands' )->whereNot( 'id', $prod->brand_id )->get();
        $result[ 'category' ] = DB::table( 'categories' )->whereNot( 'id', $prod->category_id )->get();
        $result[ 'prod' ] = $prod;

        return view( 'admin/editproduct', $result );
    }

    public function editprod_process( Request $request ) {
        $request->validate( [
            'name'=>'required|unique:products,name,'.$request->post( 'id' ),
        ] );
        $image = array();
        if ( $files = $request->file( 'images' ) ) {
            $a = 0;
            $b = '';
            foreach ( $files as $file ) {
                $a = $a + 1;
                $ext = $file->getClientOriginalExtension();
                $image_name = time().$a.'prod'.'.'.$ext;
                $image_resize = Image::make( $file->getRealPath() );
                $image_resize->resize(750, 750, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $image_resize->save( 'product/'.$image_name );
                array_push( $image, 'product/'.$image_name );
            }
        }
        $oldimg = $request->post( 'oldimg', [] );
        $prod = DB::table( 'products' )->where( 'id', $request->post( 'id' ) )->first();
        $dbimgs = explode( '|', $prod->images );
        foreach ( $dbimgs as $item ) {
            if ( in_array( $item, $oldimg ) ) {
                array_push( $image, $item );
            } else {
                if ( File::exists( $item ) ) {
                    File::delete( $item );
                }
            }
        }

        DB::table( 'products' )->where( 'id', $request->post( 'id' ) )->update( [
            'name'=>$request->post( 'name' ),
            'category_id'=>$request->post( 'category_id' ),
            'category'=>DB::table( 'categories' )->where( 'id', $request->post( 'category_id' ) )->first()->category,
            'brand_id'=>$request->post( 'brand_id' ),
            'brand'=>DB::table( 'brands' )->where( 'id', $request->post( 'brand_id' ) )->first()->name,
            'stock'=>$request->post( 'stock' ),
            'hide'=>$request->post( 'hide' ),
            'price'=>$request->post( 'price' ),
            'offer'=>$request->post( 'offer' ),
            'featured'=>$request->post( 'featured' ),
            'details'=>$request->post( 'details' ),
            'net'=>$request->post('net'),
            'images'=>implode( '|', $image )
        ] );

        return redirect( '/products' );
    }

    public function deleteproduct( Request $request, $id ) {
        $orders = DB::table( 'orders' )->where( 'product_id', $id )->get();
        $slrs = DB::table( 'salesreturns' )->where( 'product_id', $id )->get();
        if ( count( $orders ) == 0 && count($slrs) == 0) {
            $prod = Product::where( 'id', $id )->first();
            $imgs = explode( '|', $prod->images );
            foreach ( $imgs as $item ) {
                if ( File::exists( $item ) ) {
                    File::delete( $item );
                }
            }
            Product::where( 'id', $id )->delete();
            $request->session()->flash( 'error', 'Product Deleted' );
            return redirect( '/products' );
        } else {
            $request->session()->flash( 'error', 'Cannot Delete' );
            return redirect( '/products' );
        }

    }
    // public function addmp(){
        // $contents = File::get(base_path('/try.json'));
        // $json = json_decode(json: $contents, associative: true);
        // dd($json);
        // foreach($json as $item){
        //     DB::table( 'products' )->insert( [
        //         'name'=>$item['name'],
        //         'category_id'=>$item['category_id'],
        //         'category'=>$item['category'],
        //         'brand_id'=>"8",
        //         'brand'=>"MYPOWER",
        //         'stock'=>NULL,
        //         'hide'=>NULL,
        //         'price'=>$item['price'],
        //         'featured'=>NULL,
        //         'details'=>$item['details'],
        //         'images'=>implode( '|', ["product/".$item['img2'],"product/".$item['img']] )
        //     ] );
        // }
        // DB::table('products')->where("category", "powerbank")->update([
        //     'category'=>"POWERBANK",
        //     'category_id'=>"1"
        // ]);
        // DB::table('products')->where("category", "earphone")->update([
        //     'category'=>"EARPHONE",
        //     'category_id'=>"10",
        // ]);
        // DB::table('products')->where("category", "charger")->update([
        //     'category'=>"CHARGER",
        //     'category_id'=>"2",
        // ]);
        // DB::table('products')->where("category", "cable")->update([
        //     'category'=>"DATACABLE",
        //     'category_id'=>"3",
        // ]);
    //     $prod = DB::table("products")->get();
    //     foreach($prod as $item){
    //         $new = [];
    //         $img = explode("|", $item->images);
    //         foreach($img as $item2){
    //             $imgs = explode("/", $item2);
    //             if($imgs[1] != NULL){
    //                 $new[] = $item2;
    //             }
    //         }
    //         DB::table("products")->where("id", $item->id)->update([
    //             'images'=>implode("|", $new)
    //         ]);
    //         // print_r(implode("|", $new));
    //     }
    // }
    public function arrange(Request $request){
        $prod = $request->post('prod',[]);
        $prod = explode(",",$prod);
        // $a = 1;
        for ($i=0; $i < count($prod); $i++) { 
            DB::table('products')->where('id', $prod[$i])->update([
                'ordernum'=>$i+1,
            ]);
        }
        // foreach($prod as $item){
        //     DB::table("products")->where('id', $item)->update([
        //         'ordernum'=>$a,
        //     ]);
        //     $a=$a+1;
        // }
        return response()->json("Success");
    }
}
