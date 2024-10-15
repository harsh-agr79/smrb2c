<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Get the user's cart
    public function getCart(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart ?? [];

        // Check if the cart is a JSON string and decode it
        if (is_string($cart)) {
            $cart = json_decode($cart, true);  // Decode JSON string to array
        }

        // If the cart is empty after decoding, return an empty array
        if (empty($cart)) {
            return response()->json([]);
        }

        // Extract product IDs from the cart
        $productIds = array_column($cart, 'product_id');

        // Fetch product details from the database
        $products = DB::table('products')->whereIn('id', $productIds)->get()->keyBy('id');

        // Combine cart items with product data
        $fullCart = array_map(function ($item) use ($products) {
            $product = $products[$item['product_id']] ?? null;
            return [
                'product' => $product, // Include all product details
                'quantity' => $item['quantity'],
                'variation' => $item['variation'],
            ];
        }, $cart);

        return response()->json($fullCart);  // Return only the array of products
    }


    public function addToCart(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart ?? [];
    
        // Check if the cart is a JSON string and decode it
        if (is_string($cart)) {
            $cart = json_decode($cart, true);  // Decode JSON string to array
        }
    
        // Get the product and other optional data from the request
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);  // Default quantity is 1
        $variation = $request->input('variation', null);  // Variation is optional
    
        // Check if the product already exists in the cart
        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId) {
                // Update quantity if product exists in cart
                $item['quantity'] += $quantity;
                if ($variation) {
                    $item['variation'] = $variation;  // Update variation if provided
                }
                $found = true;
                break;
            }
        }
    
        // If the product does not exist in the cart, add it
        if (!$found) {
            $cart[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'variation' => $variation  // Can be null if not provided
            ];
        }
    
        // Save the updated cart to the user
        $user->cart = $cart;
        $user->save();
    
        return response()->json(['message' => 'Product added to cart successfully', 'cart' => $cart]);
    }
    

    public function updateCart(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart ?? [];

        // Check if the cart is a JSON string and decode it
        if (is_string($cart)) {
            $cart = json_decode($cart, true);  // Decode JSON string to array
        }

        // Get the product ID and new quantity/variation from the request
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);  // Default quantity is 1
        $variation = $request->input('variation', null);  // Variation is optional

        // Update the cart
        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId) {
                // Update quantity and variation if provided
                $item['quantity'] = $quantity;
                if ($variation) {
                    $item['variation'] = $variation;
                }
                break;
            }
        }

        // Save the updated cart to the user
        $user->cart = $cart;
        $user->save();

        return response()->json(['message' => 'Cart updated successfully', 'cart' => $cart]);
    }

    

    public function removeFromCart(Request $request)
{
    $user = $request->user();
    $cart = $user->cart ?? [];

    // Check if the cart is a JSON string and decode it
    if (is_string($cart)) {
        $cart = json_decode($cart, true);  // Decode JSON string to array
    }

    // Get the product ID to remove
    $productId = $request->input('product_id');

    // Filter out the product from the cart
    $cart = array_filter($cart, function($item) use ($productId) {
        return $item['product_id'] != $productId;
    });

    // Save the updated cart to the user
    $user->cart = $cart;
    $user->save();

    return response()->json(['message' => 'Product removed from cart successfully', 'cart' => $cart]);
}

    public function reduceQuantity(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart ?? [];

        // Check if the cart is a JSON string and decode it
        if (is_string($cart)) {
            $cart = json_decode($cart, true);  // Decode JSON string to array
        }

        // Get the product ID from the request
        $productId = $request->input('product_id');
        $decreaseBy = $request->input('quantity', 1);  // Default to decreasing by 1

        // Flag to check if product exists
        $found = false;

        // Loop through the cart and reduce the quantity
        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId) {
                $found = true;

                // Reduce the quantity
                $item['quantity'] -= $decreaseBy;

                // If the quantity is 0 or less, you can either remove the item or set a minimum quantity
                if ($item['quantity'] <= 0) {
                    // Remove the item from the cart
                    $cart = array_filter($cart, function($item) use ($productId) {
                        return $item['product_id'] != $productId;
                    });
                }
                break;
            }
        }

        // If the product was found, save the updated cart
        if ($found) {
            $user->cart = $cart;
            $user->save();
            return response()->json(['message' => 'Product quantity reduced successfully', 'cart' => $cart]);
        } else {
            return response()->json(['message' => 'Product not found in cart'], 404);
        }
    }

    public function addToWishlist(Request $request)
    {
        $user = $request->user();
        $wishlist = $user->wishlist ?? [];

        // Check if the wishlist is a JSON string and decode it
        if (is_string($wishlist)) {
            $wishlist = json_decode($wishlist, true);  // Decode JSON string to array
        }

        // Get the product ID from the request
        $productId = $request->input('product_id');

        // Check if the product already exists in the wishlist
        if (!in_array($productId, array_column($wishlist, 'product_id'))) {
            $wishlist[] = ['product_id' => $productId];
        }

        // Save the updated wishlist to the user
        $user->wishlist = $wishlist;
        $user->save();

        return response()->json(['message' => 'Product added to wishlist successfully', 'wishlist' => $wishlist]);
    }
    public function removeFromWishlist(Request $request)
    {
        $user = $request->user();
        $wishlist = $user->wishlist ?? [];

        // Check if the wishlist is a JSON string and decode it
        if (is_string($wishlist)) {
            $wishlist = json_decode($wishlist, true);  // Decode JSON string to array
        }

        // Get the product ID to remove
        $productId = $request->input('product_id');

        // Filter out the product from the wishlist
        $wishlist = array_filter($wishlist, function($item) use ($productId) {
            return $item['product_id'] != $productId;
        });

        // Save the updated wishlist to the user
        $user->wishlist = $wishlist;
        $user->save();

        return response()->json(['message' => 'Product removed from wishlist successfully', 'wishlist' => $wishlist]);
    }

    public function getWishlist(Request $request)
    {
        $user = $request->user();
        $wishlist = $user->wishlist ?? [];

        // Check if the wishlist is a JSON string and decode it
        if (is_string($wishlist)) {
            $wishlist = json_decode($wishlist, true);  // Decode JSON string to array
        }

        // Fetch all product data for each product in the wishlist
        $productIds = array_column($wishlist, 'product_id');
        $products = DB::table('products')->whereIn('id', $productIds)->get();

        return response()->json(['wishlist' => $products]);
    }





}
