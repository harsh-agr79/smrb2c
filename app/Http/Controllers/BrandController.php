<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Image;

class BrandController extends Controller
{
    public function index(){
            $result['data'] = Brand::get();
            return view('admin.brands',$result);
    }
    public function getbrand($id){
        $brand = Brand::where('id', $id)->first();
        return response()->json($brand, 200);
    }
    public function editbrand(Request $request){
        $request->validate([
            'name'=>'required|unique:brands,name,'.$request->post('id'),       
        ]);
        if($file = $request->file('logo')){    
            if(File::exists($request->post('oldlogo'))) {
                File::delete($request->post('oldlogo'));
            }
            $file = $request->file('logo');
            $ext = $file->getClientOriginalExtension();
            $image_name = $request->post('id').time().'logo'.'.'.$ext;
            $image_resize = Image::make($file->getRealPath());
            $image_resize->fit(300);
            $image_resize->save('logos/'.$image_name);
            $image = 'logos/'.$image_name;
            DB::table('brands')->where('id', $request->post('id'))->update([
                'logo'=>$image
            ]);
        }
        $name = $request->post('name');
        $info = $request->post('info');
        $id = $request->post('id');

        DB::table('brands')->where('id', $id)->update([
            'name'=>$name,
            'info'=>$info,
        ]);
        DB::table('products')->where('brand_id', $id)->update([
            'brand'=>$name
        ]);
        return response()->json($request->post(), 200);
    }
    public function getbranddata(){
        $brand = Brand::get();
        return response()->json($brand,200);
    }
    public function addbrand(Request $request){
        $request->validate([
            'name'=>'required|unique:brands,name,',           
        ]);
        $name = $request->post('name');
        $info = $request->post('info');
        if($file = $request->file('logo')){
            $file = $request->file('logo');
            $ext = $file->getClientOriginalExtension();
            $image_name = $request->post('id').time().'logo'.'.'.$ext;
            $image_resize = Image::make($file->getRealPath());
            $image_resize->fit(300);
            $image_resize->save('logos/'.$image_name);
            $image = 'logos/'.$image_name;
        }
        else{
            $image = NULL;
        }
        DB::table('brands')->insert([
            'name'=>$name,
            'info'=>$info,
            'logo'=>$image,
        ]);
        return response()->json($request->post('id'), 200);
    }
    public function delbrand($id){
            Brand::where('id', $id)->delete();
            return response()->json("Brand Deleted!", 200);
    }
}
