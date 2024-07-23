<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;


class StaffController extends Controller
{
    public function staff(){
        $result['data'] = DB::table('staffs')->get();

        return view('admin/staff', $result);
    }

    public function addstaff(Request $request,$id=''){

        if($id > 0){
            $data = DB::table('staffs')->where('id', $id)->first();
           $result['id'] = $data->id;
           $result['name'] = $data->name;
           $result['userid'] = $data->userid;
           $result['contact'] = $data->contact;
           $result['password'] = $data->password;

           $result['permission'] = explode("|", $data->permission);
        }
        else{
           $result['id'] = '';
           $result['name'] = '';
           $result['userid'] = '';
           $result['contact'] = '';
           $result['password'] = '';

           $result['permission'] = [];
        }
        return view('admin/addstaff', $result);
    }

    public function addstaff_process(Request $request){

        // dd($request->post());
        $id = $request->get('id');
        $name = $request->get('name');
        $userid = $request->get('userid');
        $contact = $request->get('contact');
        $perms = $request->post('perm', []);
        if($request->get('passwordnew')){
            $password = Hash::make($request->get('passwordnew'));
        }
        else{
            $password = $request->get('passwordold');
        }

        $request->validate([
            'userid'=>'required|unique:admins,userid|unique:customers,userid|unique:marketers,userid|unique:staffs,userid,'.$request->post('id')
        ]);

        if($id>0){
            DB::table('staffs')->where('id', $id)->update([
                'name'=>$name,
                'userid'=>$userid,
                'contact'=>$contact,
                'password'=>$password,
                'permission'=>implode("|", $perms)
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
            DB::table('staffs')->insert([
                'name'=>$name,
                'userid'=>$userid,
                'contact'=>$contact,
                'password'=>Hash::make($password),
                'permission'=>implode("|", $perms)
            ]);
        }

        return redirect(url()->previous());
    }
}
