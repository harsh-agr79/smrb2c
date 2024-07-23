<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Image;


class CustomerController extends Controller
{

    public function customers(){
        $result['data'] = Customer::get();
        return view('admin.customers',$result);
    }
    public function addcustomer(){
        $result['brands'] = DB::table('brands')->get();
        $result['marketers'] = DB::table('marketers')->get();
        return view('admin/addcustomers', $result);
    }
    public function addcus_process(Request $request){
        $request->validate([
            'userid'=>'required|unique:marketers,userid|unique:staffs,userid|unique:admins,userid|unique:customers,userid,'.$request->post('id'),        
            'email'=>'required|unique:admins,email|unique:customers,email,'.$request->post('id'), 
            'contact'=>'required|unique:customers,contact,'.$request->post('id'),
            'tax_number'=>'required|unique:customers,tax_number,'.$request->post('id'),                      
        ]);

        if($request->post('marketer') != NULL){
            $marketer_id=$request->post('marketer');
            $marketer=DB::table('marketers')->where('id', $request->post('marketer'))->first()->userid;
        }
        else{
            $marketer_id=NULL;
            $marketer=NULL;
        }

        
       DB::table('customers')->insert([
        'name'=>$request->post('name'),
        'shopname'=>$request->post('shopname'),
        'email'=>$request->post('email'),
        'userid'=>$request->post('userid'),
        'uniqueid'=>$request->post('userid').time().rand(1,1000000),
        'password'=>Hash::make($request->post('password')),
        'dob'=>$request->post('dob'),
        'contact'=>$request->post('contact'),
        'contact2'=>$request->post('contact2'),
        'address'=>$request->post('address'),
        'area'=>$request->post('area'),
        'state'=>$request->post('state'),
        'district'=>$request->post('district'),
        'marketer_id'=>$marketer_id,
        'marketer'=>$marketer,
        'tax_type'=>$request->post('tax_type'),
        'tax_number'=>$request->post('tax_number'),
        'type'=>$request->post('type'),
        'brands'=>implode("|", $request->post('brands', []))
       ]);
       return redirect('/customers');
    }
    public function editcustomer($id){
        $result['cus'] = DB::table('customers')->where('id', $id)->first();
        $result['brands'] = DB::table('brands')->get();
        $result['marketers'] = DB::table('marketers')->get();

        return view('admin/editcustomer', $result);
    }
    public function editcus_process(Request $request){
        $request->validate([
            'userid'=>'required|unique:marketers,userid|unique:staffs,userid|unique:admins,userid|unique:customers,userid,'.$request->post('id'),
            'email'=>'required|unique:admins,email|unique:customers,email,'.$request->post('id'), 
            'contact'=>'required|unique:customers,contact,'.$request->post('id'),
            'tax_number'=>'unique:customers,tax_number,'.$request->post('id'),                      
        ]);

        if($file = $request->file('dp')){
            if(File::exists($request->post('olddp'))) {
                File::delete($request->post('olddp'));
            }
            $file = $request->file('dp');
            $ext = $file->getClientOriginalExtension();
            $image_name = $request->post('id').time().'userdp'.'.'.$ext;
            $image_resize = Image::make($file->getRealPath());
            $image_resize->fit(300);
            $image_resize->save('customerdp/'.$image_name);
            $image = 'customerdp/'.$image_name;
                DB::table('customers')->where('id', $request->post('id'))->update([
                    'profileimg'=>$image
                ]);
        }

        if($request->password == NULL){
            $password = DB::table('customers')->where('id', $request->post('id'))->first()->password;
        }
        else{
            $password = Hash::make($request->post('password'));
        }
        if($request->post('marketer') != NULL){
            $marketer_id=$request->post('marketer');
            $marketer=DB::table('marketers')->where('id', $request->post('marketer'))->first()->userid;
        }
        else{
            $marketer_id=NULL;
            $marketer=NULL;
        }
        
        $brands = $request->post('brands', []);
        DB::table('customers')->where('id', $request->post('id'))->where('uniqueid', $request->post('uniqueid'))->update([
            'name'=>$request->post('name'),
            'shopname'=>$request->post('shopname'),
            'email'=>$request->post('email'),
            'userid'=>$request->post('userid'),
            'password'=>$password,
            'dob'=>$request->post('dob'),
            'contact'=>$request->post('contact'),
            'contact2'=>$request->post('contact2'),
            'address'=>$request->post('address'),
            'area'=>$request->post('area'),
            'state'=>$request->post('state'),
            'district'=>$request->post('district'),
            'marketer_id'=>$marketer_id,
            'marketer'=>$marketer,
            'tax_type'=>$request->post('tax_type'),
            'tax_number'=>$request->post('tax_number'),
            'type'=>$request->post('type'),
            'brands'=>implode("|", $brands)
           ]);

           DB::table('orders')->where('user_id', $request->post('id'))->update([
            'marketer_id'=>$marketer_id,
            'marketer'=>$marketer,
           ]);
        
           return redirect('/customers');
    }
    public function deletecustomer(Request $request,$id){
        $orders = DB::table("orders")->where("user_id", $id)->get();
        $payments = DB::table("payments")->where("user_id", $id)->get();
        $salesreturns = DB::table("salesreturns")->where("user_id", $id)->get();
        $expenses = DB::table("expenses")->where("user_id", $id)->get();
        if(count($orders)>0 || count($payments)>0 || count($salesreturns)>0 || count($expenses)>0){
            $request->session()->flash('error','Cannot Delete Customer Other Data Exists');
            return redirect('/customers');
        }
        else{
            Customer::where('id', $id)->delete();
            $request->session()->flash('error','Customer Deleted');
            return redirect('/customers');
        }      
    }

    public function getcustomer(){
        $c = DB::table('customers')->get();
        return response()->json($c);
    }
}
