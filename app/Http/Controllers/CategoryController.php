<?php

// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index(){
        $result['data'] = Category::orderBy('ordernum', 'ASC')->get();
        return view('admin.category', $result);
    }

    public function getCategoryApi(){
        $category = Category::orderBy('ordernum', 'ASC')->get();
        return response()->json($category, 200);
    }
    public function homeCategory() {
        // Fetch categories where image is not null
        $categories = Category::whereNotNull('image')->get();
        
        // Add the prefix 'categories/' to each image value
        $categories->map(function ($category) {
            if ($category->image) {
                $category->image = 'categories/' . $category->image;
            }
            return $category;
        });
    
        // Return the updated categories with the prefixed image path
        return response()->json($categories, 200);
    }
    

    public function getcategory($id){
        $category = Category::where('id', $id)->first();
        return response()->json($category, 200);
    }

    public function editcategory(Request $request){
        $request->validate([
            'category' => 'required|unique:categories,category,' . $request->post('id'),
            'image' => 'nullable|image|mimes:png,webp|max:2048', // Validate image as PNG, max 2MB
        ]);

        $categoryName = $request->post('category');
        $id = $request->post('id');

        $category = Category::find($id);
        $category->category = $categoryName;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image && File::exists(public_path('categories/' . $category->image))) {
                File::delete(public_path('categories/' . $category->image));
            }

            // Store new image
            $image = $request->file('image');
            $imageName = 'category_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('categories'), $imageName);
            $category->image = $imageName;
        }

        $category->save();

        // Update related tables if necessary
        // Assuming related tables store category name; consider if they should store category_id instead
        DB::table('products')->where('category_id', $id)->update(['category' => $categoryName]);
        DB::table('orders')->where('category_id', $id)->update(['category' => $categoryName]);
        DB::table('salesreturns')->where('category_id', $id)->update(['category' => $categoryName]);

        return response()->json($category, 200);
    }

    public function getcategorydata(){
        $category = Category::get();
        return response()->json($category,200);
    }

    public function addcategory(Request $request){
        $request->validate([
            'category' => 'required|unique:categories,category',
            'image' => 'required|image|mimes:png,webp|max:2048', // Validate image as PNG, max 2MB
        ]);

        $categoryName = $request->post('category');

        if ($request->hasFile('image')) {
            // Store image
            $image = $request->file('image');
            $imageName = 'category_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('categories'), $imageName);
        } else {
            $imageName = null;
        }

        $category = Category::create([
            'category' => $categoryName,
            'image' => $imageName,
        ]);

        return response()->json($category, 200);
    }

    public function delcategory($id){
        $category = Category::find($id);
        if ($category) {
            // Delete image if exists
            if ($category->image && File::exists(public_path('categories/' . $category->image))) {
                File::delete(public_path('categories/' . $category->image));
            }

            // Delete category
            $category->delete();

            // Optionally, handle related records if necessary
            DB::table('products')->where('category_id', $id)->update(['category' => null]);
            DB::table('orders')->where('category_id', $id)->update(['category' => null]);
            DB::table('salesreturns')->where('category_id', $id)->update(['category' => null]);

            return response()->json("Category Deleted!", 200);
        }

        return response()->json("Category not found!", 404);
    }

    public function arrange(Request $request){
        $prod = $request->post('prod',[]);
        $prod = explode(",",$prod);
        // $a = 1;
        for ($i=0; $i < count($prod); $i++) { 
            DB::table('categories')->where('id', $prod[$i])->update([
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
