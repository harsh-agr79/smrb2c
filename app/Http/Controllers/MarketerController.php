<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;


class MarketerController extends Controller
{
    public function marketer(){
        $result['data'] = DB::table('marketers')->get();

        return view('admin/marketer', $result);
    }

    public function addmarketer(Request $request,$id=''){

        if($id > 0){
            $data = DB::table('marketers')->where('id', $id)->first();
           $result['id'] = $data->id;
           $result['name'] = $data->name;
           $result['userid'] = $data->userid;
           $result['contact'] = $data->contact;
           $result['password'] = $data->password;
        }
        else{
           $result['id'] = '';
           $result['name'] = '';
           $result['userid'] = '';
           $result['contact'] = '';
           $result['password'] = '';
        }
        return view('admin/addmarketer', $result);
    }

    public function addmarketer_process(Request $request){

        // dd($request->post());
        $id = $request->get('id');
        $name = $request->get('name');
        $userid = $request->get('userid');
        $contact = $request->get('contact');
        if($request->get('passwordnew')){
            $password = Hash::make($request->get('passwordnew'));
        }
        else{
            $password = $request->get('passwordold');
        }

        $request->validate([
            'userid'=>'required|unique:admins,userid|unique:staffs,userid|unique:customers,userid|unique:marketers,userid,'.$request->post('id')
        ]);

        if($id>0){
            DB::table('marketers')->where('id', $id)->update([
                'name'=>$name,
                'userid'=>$userid,
                'contact'=>$contact,
                'password'=>$password,
            ]);
            // $initial = $request->post('name2');
            // $initialid = $request->post('userid2');
            // DB::table('orders')->where('refname', $initial)->update([
            //     'refname'=>$name
            // ]);
            // DB::table('customers')->where('refname', $initial)->update([
            //     'refname'=>$name
            // ]);
            // DB::table('orders')->where('seenby', $initialid)->update([
            //     'seenby'=>$userid
            // ]);
            // DB::table('permission')->where('userid', $id)->delete();
        }
        else{
            DB::table('marketers')->insert([
                'name'=>$name,
                'userid'=>$userid,
                'contact'=>$contact,
                'password'=>Hash::make($password),
            ]);
        }

        return redirect('/marketer');
    }
}
