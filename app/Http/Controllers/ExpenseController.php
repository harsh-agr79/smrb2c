<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ExpenseController extends Controller
{
    public function index(Request $request){
        $query = DB::table('expenses')->orderBy('date', 'DESC');
        $result['date'] = '';
        $result['date2'] =  '';
        $result['name'] =  '';
        if($request->get('date')){
            $query = $query->where('date', '>=', $request->get('date'));
            $result['date'] =  $request->get('date');
        }
        if($request->get('date2')){
            $query = $query->where('date', '<=', $request->get('date2'));
            $result['date2'] =  $request->get('date2');
        }
        if($request->get('name')){
            $query = $query->where('name', $request->get('name'));
            $result['$name'] =  $request->get('name');
        }
        $query = $query->paginate(100);
        $result['data'] = $query;
        return view('admin/expense', $result);
    }
    public function addexp(Request $request, $id = ''){
        if($id !== ""){
            $exp = DB::table('expenses')->where('expenseid', $id)->first();
            $result['date'] = $exp->date;
            $result['name'] = $exp->name;
            $result['amount'] = $exp->amount;
            $result['particular'] = $exp->particular;
            $result['expid'] = $exp->expenseid;
        }
        else{
            $result['date'] = date('Y-m-d');
            $result['name'] = '';
            $result['amount'] = '';
            $result['particular'] = '';
            $result['expid'] = '';
        }
        return view('admin/addexpense', $result);
    }
    public function addexp_process(Request $request){
        $expid = $request->post('expid');
        if($expid === NULL){
            DB::table('expenses')->insert([
                'date'=>$request->post('date'),
                'name'=>$request->post('name'),
                'user_id'=>DB::table('customers')->where('name', $request->post('name'))->first()->id,
                'expenseid'=>'exp'.date('sihdmY'),
                'amount'=>$request->post('amount'),
                'particular'=>$request->post('particular'),
            ]);
            updatebalance(DB::table('customers')->where('name', $request->post('name'))->first()->id);
            return redirect('addexpense');
        }
        else{
            DB::table('expenses')->where('expenseid', $expid)->update([
                'date'=>$request->post('date'),
                'name'=>$request->post('name'),
                'user_id'=>DB::table('customers')->where('name', $request->post('name'))->first()->id,
                'amount'=>$request->post('amount'),
                'particular'=>$request->post('particular'),
            ]);
            updatebalance(DB::table('customers')->where('name', $request->post('name'))->first()->id);
            return redirect('expenses');
        }
    } 

    public function deleteexp(Request $request, $id){
        $userid = DB::table('expenses')->where('expenseid', $id)->first()->user_id;
        DB::table('expenses')->where('expenseid', $id)->delete();
        updatebalance($userid);
        return redirect('/expenses');
    }
}
