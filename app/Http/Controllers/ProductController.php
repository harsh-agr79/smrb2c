<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Image;


class ProductController extends Controller {
    public function getproduct(Request $request) {
        // Start the product query
        $query = DB::table('products')->orderBy('ordernum', 'ASC')->whereNull('hide');
    
        // Apply filters based on the request parameters
        if ($request->has('brand')) {
            $query->where('brand_id', $request->get('brand'));
        }
        if ($request->has('category')) {
            $query->where('category_id', $request->get('category'));
        }
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->get('price_min'));
        }
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->get('price_max'));
        }
        if ($request->has('stock')) {
            $query->where('stock', $request->get('stock'));
        }
        if ($request->has('featured')) {
            $query->where('featured', $request->get('featured'));
        }
        if ($request->has('new')) {
            $query->where('new', $request->get('new'));
        }
        if ($request->has('flash')) {
            $query->where('flash', $request->get('flash'));
        }
        if ($request->has('trending')) {
            $query->where('trending', $request->get('trending'));
        }
    
        // Retrieve the authenticated user and their wishlist
        $user = auth('sanctum')->user();
        $wishlistProductIds = [];

        if ($user && !empty($user->wishlist)) {
                $wishlist = json_decode($user->wishlist, true);
                if (is_array($wishlist)) {
                    // Extract the product_ids from the wishlist
                    $wishlistProductIds = array_column($wishlist, 'product_id');
                }
        }
    
        // Execute the query and get the results
        $products = $query->get();
    
        // Add the wishlist field to each product and decode variations
        $products->transform(function($product) use ($wishlistProductIds) {
            $product->variations = json_decode($product->variations, true); // Decode JSON to associative array
    
            // Check if the product is in the wishlist
            $product->wishlist = in_array($product->id, $wishlistProductIds);
    
            return $product;
        });
    
        // Return the results as a JSON response
        return response()->json($products);
    }
    

    public function getproduct2(Request $request) {
        // Start the query for fetching products
        $query = \DB::table('products')->whereNull('hide')->orderBy('ordernum', 'ASC');
    
        // Apply filters for brand and category (arrays of IDs)
        if ($request->has('brand')) {
            $brandIds = $request->get('brand');
            if (is_string($brandIds) && preg_match('/^\[.*\]$/', $brandIds)) {
                $brandIds = json_decode($brandIds, true);
            }
            if (is_array($brandIds)) {
                $query->whereIn('brand_id', $brandIds);
            } else {
                $query->where('brand_id', $brandIds);
            }
        }
    
        if ($request->has('category')) {
            $categoryIds = $request->get('category');
            if (is_string($categoryIds) && preg_match('/^\[.*\]$/', $categoryIds)) {
                $categoryIds = json_decode($categoryIds, true);
            }
            if (is_array($categoryIds)) {
                $query->whereIn('category_id', $categoryIds);
            } else {
                $query->where('category_id', $categoryIds);
            }
        }
    
        // Apply other filters (price range, stock, etc.)
        if ($request->has('new')) {
            $query->where('new', $request->get('new'));
        }
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->get('price_min'));
        }
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->get('price_max'));
        }
        if ($request->has('stock')) {
            $query->where('stock', $request->get('stock'));
        }
        if ($request->has('featured')) {
            $query->where('featured', $request->get('featured'));
        }
        if ($request->has('flash')) {
            $query->where('flash', $request->get('flash'));
        }
        if ($request->has('trending')) {
            $query->where('trending', $request->get('trending'));
        }
    
        // Universal search logic
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
    
            $searchableFields = ['name', 'brand', 'category', 'price', 'details'];
            $specialFields = ['featured', 'trending', 'flash', 'offer', 'new'];
    
            if (in_array($searchTerm, $specialFields)) {
                $query->whereNotNull($searchTerm)->orWhere($searchTerm, true);
            } else {
                $query->where(function ($q) use ($searchableFields, $searchTerm) {
                    foreach ($searchableFields as $field) {
                        $q->orWhere($field, 'LIKE', '%' . $searchTerm . '%');
                    }
                });
            }
        }
    
        // Retrieve the authenticated user and their wishlist
        $user = auth('sanctum')->user();
        $wishlistProductIds = [];

        if ($user && !empty($user->wishlist)) {
            $wishlist = json_decode($user->wishlist, true);
            if (is_array($wishlist)) {
                // Extract the product_ids from the wishlist
                $wishlistProductIds = array_column($wishlist, 'product_id');
            }
        }
    
        // Execute the query and paginate the results
        $results = $query->paginate(20);
        $results->getCollection()->transform(function($product) use ($wishlistProductIds) {
            $product->variations = json_decode($product->variations, true); // Decode JSON to associative array
            $product->wishlist = in_array($product->id, $wishlistProductIds); // Add wishlist field
            return $product;
        });
    
        return response()->json($results);
    }
    

    public function maxPrice(){
        $maxPrice = DB::table('products')->max('price');
    
        return response()->json($maxPrice);
    }
    

    public function getproductdetail( Request $request, $id ) {
        $c = DB::table( 'products' )->where( 'id', $id )->first();
        $c->variations = json_decode($c->variations);
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

    public function addprod_process(Request $request)
    {
        // dd($request->post());
        $request->validate([
            'name' => 'required|unique:products,name,' . $request->post('id'),
            'price' => 'required|numeric',  // Main product price
            'variations' => 'nullable|array', // Allow variations to be null
            'variations.*.specification_1' => 'nullable|string',
            'variations.*.specification_2' => 'nullable|string',
            'variations.*.colors' => 'nullable|string',
            'variations.*.price' => 'nullable|numeric'  // Variation price
        ]);
    
        $image = array();
        if ($files = $request->file('images')) {
            $a = 0;
            foreach ($files as $file) {
                $a++;
                $ext = $file->getClientOriginalExtension();
                $image_name = time() . $a . 'prod' . '.' . $ext;
                $image_resize = Image::make($file->getRealPath());
                $image_resize->save('product/' . $image_name);
                array_push($image, 'product/' . $image_name);
            }
        }
    
        // Convert colors (comma-separated) to array format
        $variations = $request->post('variations') ? array_map(function ($variation) {
            $variation['colors'] = explode(',', $variation['colors']);
            return $variation;
        }, $request->post('variations')) : null; // Set to null if no variations
    
        DB::table('products')->insert([
            'name' => $request->post('name'),
            'category_id' => $request->post('category_id'),
            'category' => DB::table('categories')->where('id', $request->post('category_id'))->first()->category,
            'brand_id' => $request->post('brand_id'),
            'brand' => DB::table('brands')->where('id', $request->post('brand_id'))->first()->name,
            'stock' => $request->post('stock'),
            'hide' => $request->post('hide'),
            'price' => $request->post('price'), // Main product price
            'offer' => $request->post('offer'),
            'featured' => $request->post('featured'),
            'new' => $request->post('new'),
            'flash' => $request->post('flash'),
            'trending' => $request->post('trending'),
            'details' => $request->post('details'),
            'net' => $request->post('net'),
            'images' => implode('|', $image),
            'variations' => $variations ? json_encode($variations) : null, // Store variations as JSON or null
            'ordernum' => 100000
        ]);
    
        return redirect('/products');
    }
    

    public function editproduct( $id ) {
        $prod = DB::table( 'products' )->where( 'id', $id )->first();
        $result[ 'brands' ] = DB::table( 'brands' )->whereNot( 'id', $prod->brand_id )->get();
        $result[ 'category' ] = DB::table( 'categories' )->whereNot( 'id', $prod->category_id )->get();
        $result[ 'prod' ] = $prod;

        return view( 'admin/editproduct', $result );
    }

    public function editprod_process(Request $request) {
        //dd($request->post());
        $request->validate([
            'name' => 'required|unique:products,name,' . $request->post('id'),
            'price' => 'required|numeric',  // Main product price
            'variations' => 'nullable|array', // Allow variations to be null
            'variations.*.specification_1' => 'nullable|string',
            'variations.*.specification_2' => 'nullable|string',
            'variations.*.colors' => 'nullable|string',
            'variations.*.price' => 'nullable|numeric'  // Variation price
        ]);
    
        $image = [];
        if ($files = $request->file('images')) {
            $a = 0;
            foreach ($files as $file) {
                $a++;
                $ext = $file->getClientOriginalExtension();
                $image_name = time() . $a . 'prod' . '.' . $ext;
                $image_resize = Image::make($file->getRealPath());
                $image_resize->resize(750, 750, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $image_resize->save('product/' . $image_name);
                $image[] = 'product/' . $image_name;
            }
        }
    
        // Convert colors (comma-separated) to array format, if variations exist
        $variations = $request->post('variations') ? array_map(function ($variation) {
            $variation['colors'] = explode(',', $variation['colors']);
            return $variation;
        }, $request->post('variations')) : null; // Set to null if no variations
    
        // Retrieve existing product data
        $prod = DB::table('products')->where('id', $request->post('id'))->first();
        $dbimgs = explode('|', $prod->images);
        foreach ($dbimgs as $item) {
            if (!in_array($item, $request->post('oldimg', []))) {
                if (File::exists($item)) {
                    File::delete($item);
                }
            } else {
                $image[] = $item;  // Keep existing images
            }
        }
    
        // Update product data
        DB::table('products')->where('id', $request->post('id'))->update([
            'name' => $request->post('name'),
            'category_id' => $request->post('category_id'),
            'category' => DB::table('categories')->where('id', $request->post('category_id'))->first()->category,
            'brand_id' => $request->post('brand_id'),
            'brand' => DB::table('brands')->where('id', $request->post('brand_id'))->first()->name,
            'stock' => $request->post('stock'),
            'hide' => $request->post('hide'),
            'price' => $request->post('price'),  // Main product price
            'offer' => $request->post('offer'),
            'featured' => $request->post('featured'),
            'new' => $request->post('new'),
            'flash' => $request->post('flash'),
            'trending' => $request->post('trending'),
            'details' => $request->post('details'),
            'net' => $request->post('net'),
            'images' => implode('|', $image),
            'variations' => $variations ? json_encode($variations) : null  // Store variations as JSON or null
        ]);
    
        return redirect('/products');
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

    public function maxDiscount() {
        // Retrieve price and offer values from the database
        $products = DB::table('products')->select('price', 'offer')->get();
    
        // Calculate discount percentages
        $discounts = $products->map(function($product) {
            if ($product->price > 0 && $product->offer != NULL) {
                return (($product->price - $product->offer) / $product->price) * 100;
            }
            return 0; // Avoid division by zero for products with price 0
        });
    
        // Get the maximum discount percentage
        $maxDiscountPercentage = $discounts->max();
    
        // Return the result as JSON
        return response()->json($maxDiscountPercentage);
    }
    
}
