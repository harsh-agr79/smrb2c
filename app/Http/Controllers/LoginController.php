<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function auth(Request $request){
        $userid = $request->post('userid');
        $password = $request->post('password');

        $admin = DB::table('admins')->where(['userid'=>$userid])->first();
        $customer = DB::table('customers')->where('userid',$userid)->first();
        $staff = DB::table('staffs')->where('userid',$userid)->first();
        $marketer = DB::table('marketers')->where('userid',$userid)->first();
        if($admin!=NULL){
            if (Hash::check($request->post('password'), $admin->password)) {
                $request->session()->put('ADMIN_LOGIN', true);
                $request->session()->put('ADMIN_ID', $admin->id);
                $request->session()->put('ADMIN_TIME', time() );
                $request->session()->put('ADMIN_TYPE', $admin->type);
    
                return redirect('/');
            }
            else{
                $request->session()->flash('error','please enter valid login details');
                return redirect('/');
            }
        }
        elseif($customer!=NULL){
            if (Hash::check($request->post('password'), $customer->password)) {
                $request->session()->put('USER_LOGIN', true);
                $request->session()->put('USER_ID', $customer->id);
                $request->session()->put('USER_TIME', time() );
    
                return redirect('/');
                }
                else{
                    $request->session()->flash('error','please enter valid login details');
                    return redirect('/');
                }
        }
        elseif($staff!=NULL){
            if (Hash::check($request->post('password'), $staff->password)) {
                $request->session()->put('ADMIN_LOGIN', true);
                $request->session()->put('ADMIN_ID', $staff->id);
                $request->session()->put('STAFF_ID', $staff->id);
                $request->session()->put('ADMIN_TIME', time() );
                $request->session()->put('ADMIN_TYPE', 'staff');
    
                return redirect('/');
            }
            else{
                $request->session()->flash('error','please enter valid login details');
                return redirect('/');
            }
        }
        elseif($marketer!=NULL){
            if (Hash::check($request->post('password'), $marketer->password)) {
                $request->session()->put('MARKETER_LOGIN', true);
                $request->session()->put('MARKETER_ID', $marketer->id);
                $request->session()->put('MARKETER_TIME', time() );
    
                return redirect('/');
            }
            else{
                $request->session()->flash('error','please enter valid login details');
                return redirect('/');
            }
        }
        else{
            $request->session()->flash('error','please enter valid login details');
            return redirect('/');
        }
    }
    public function dashboard(){
        // $result['dealer'] = DB::table('orders')
        // ->where(['orders.deleted'=>NULL, 'save'=>NULL])
        // ->havingBetween('orders.created_at', [today()->subDays(1), today()->addDays(1)])
        // ->orderBy('orders.created_at', 'DESC')
        // ->join('customers', 'customers.cusuni_id', '=', 'orders.cusuni_id')
        // ->selectRaw('orders.name,orders.created_at,orders.refname, orderid, mainstatus, seen, seenby, delivered, clnstatus, SUM(approvedquantity * price) as sla, SUM(discount * 0.01 * approvedquantity * price) as disa, SUM(quantity * price) as sl, SUM(discount * 0.01 * quantity * price) as dis')
        // ->groupBy('orders.orderid')
        // ->get();

        $result['mpe'] = DB::table('orders')
        ->where(['orders.deleted_at'=>NULL, 'save'=>NULL])
        ->havingBetween('orders.date', [today()->subDays(1), today()->addDays(1)])
        ->orderBy('orders.date', 'DESC')
        ->join('customers', 'customers.id', '=', 'orders.user_id')
        ->selectRaw('orders.name,orders.date,orders.marketer, order_id, user_id, mainstatus, seen, seenby, delivered, clnstatus, SUM(approvedquantity * price) as sla, SUM(discount * 0.01 * approvedquantity * price) as disa, SUM(quantity * price) as sl, SUM(discount * 0.01 * quantity * price) as dis')
        ->groupBy('orders.order_id')
        ->get();

        $result['pending'] = DB::table('orders')
        ->where(['orders.deleted_at'=>NULL, 'save'=>NULL, 'status'=>'pending'])
        ->orderBy('orders.date', 'DESC')
        ->join('customers', 'customers.id', '=', 'orders.user_id')
        ->selectRaw('orders.name,orders.date,orders.marketer, order_id, user_id,mainstatus, seen, seenby, delivered, clnstatus,SUM(quantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')
        ->groupBy('orders.order_id')
        ->get();
        return view('admin/dashboard', $result);
    }
}
