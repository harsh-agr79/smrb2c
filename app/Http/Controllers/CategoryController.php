<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(){
            $result['data'] = Category::get();
            return view('admin.category',$result);
    }
    public function getcategory($id){
        $category = Category::where('id', $id)->first();
        return response()->json($category, 200);
    }
    public function editcategory(Request $request){
        $request->validate([
            'category'=>'required|unique:categories,category,'.$request->post('id'),       
        ]);

        $category = $request->post('category');
        $id = $request->post('id');

        DB::table('categories')->where('id', $id)->update([
            'category'=>$category
        ]);
        DB::table('products')->where('category_id', $id)->update([
            'category'=>$category
        ]);
        DB::table('orders')->where('category_id', $id)->update([
            'category'=>$category
        ]);
        DB::table('salesreturns')->where('category_id', $id)->update([
            'category'=>$category
        ]);
        return response()->json($request->post(), 200);
    }
    public function getcategorydata(){
        $category = Category::get();
        return response()->json($category,200);
    }
    public function addcategory(Request $request){
        $request->validate([
            'category'=>'required|unique:categories,category,',           
        ]);
        $category = $request->post('category');
        DB::table('categories')->insert([
            'category'=>$category
        ]);
        return response()->json($request->post('id'), 200);
    }
    public function delcategory($id){
            Category::where('id', $id)->delete();
            return response()->json("Category Deleted!", 200);
    }
}
