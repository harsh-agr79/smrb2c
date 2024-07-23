<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AnalyticsController extends Controller
{
    public function statement(Request $request){
        $cust = DB::table('customers')->orderBy('name', 'ASC')->get();

        $data = array();
        
        foreach($cust as $item){
            $fif = DB::table('orders')
            ->where(['deleted_at'=>NULL,'save'=>NULL])
            ->where('user_id', $item->id)
            ->where('status','approved') 
            ->whereBetween('date', [now()->subDays(15), now()->addDays(1)])
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')->groupBy('name')
            ->get();

            $twe = DB::table('orders')
            ->where(['deleted_at'=>NULL])
            ->where('user_id', $item->id)
            ->whereBetween('date', [now()->subDays(25), now()->addDays(1)])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')->groupBy('name')
            ->get();

            $thir = DB::table('orders')
            ->where(['deleted_at'=>NULL])
            ->where('user_id', $item->id)
            ->whereBetween('date', [now()->subDays(35), now()->addDays(1)])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')->groupBy('name')
            ->get();
            $fou = DB::table('orders')
            ->where(['deleted_at'=>NULL])
            ->where('user_id', $item->id)
            ->whereBetween('date', [now()->subDays(45), now()->addDays(1)])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')->groupBy('name')
            ->get();

            $bal = explode('|', $item->balance);

            if($bal[0] == 'red'){
                if (count($fif) > 0) {
                    $a = $bal[1] - $fif[0]->sl;
                }
                else{
                    $a = $bal[1];
                }
                if (count($twe) > 0) {
                    $b = $bal[1] - $twe[0]->sl;
                }
                else{
                    $b = $bal[1];
                }
                if (count($thir) > 0) {
                    $c = $bal[1] - $thir[0]->sl;
                }
                else{
                    $c = $bal[1];
                }
                if (count($fou) > 0) {
                    $d = $bal[1] - $fou[0]->sl;
                }
                else{
                    $d = $bal[1];
                }
            }
            else{
                $a = 0;
                $b = 0;
                $c = 0;
                $d = 0;
            }

            $data[] = [
                'id'=>$item->id,
                'name' => $item->name,
                'shopname' => $item->shopname,
                'type'=>$item->type,
                'bal_type' => $bal[0],
                'balance' => $bal[1],
                'fif'=>$a,
                'twe'=>$b,
                'thir'=>$c,
                'fou'=>$d
            ];
        }

        $result['data'] = collect($data);

        

        return view('admin/statement', $result);
    }
    public function balancesheet(Request $request, $id){

        $cust = DB::table('customers')->where('id', $id)->first();
        $result['cus'] = $cust;
        $today = date('Y-m-d');
        
            if($request->get('date') && $request->get('date2'))
            {
                $date = $request->get('date');
                $date2 = $request->get('date2');
            }
            elseif($request->get('date')){
                $date = $request->get('date');
                $date3 = date('Y-09-17');
                $date2 = date('Y-m-d', strtotime($date3. ' + 1 year -1 day'));
            }
            elseif($request->get('date2')){
                $date2 = $request->get('date2');
                $date = date('Y-09-17');
            }
            elseif($request->get('clear')){
               if(date('Y-m-d') < date('Y-09-17') ){
                $date2 = date('Y-09-16');  
                $date = date('Y-m-d', strtotime($date2. ' -1 year +1 day'));
               }
               else{
                   $date = date('Y-09-17');
                   $date2 = date('Y-m-d', strtotime($date. ' + 1 year -1 day'));
               }
            }
            else{
                if(date('Y-m-d') < date('Y-09-17') ){
                $date2 = date('Y-09-16');  
                $date = date('Y-m-d', strtotime($date2. ' -1 year +1 day'));
               }
               else{
                   $date = date('Y-09-17');
                   $date2 = date('Y-m-d', strtotime($date. ' + 1 year -1 day'));
               }
            }
        //  }
        $result['date'] = $date;
        $result['date2'] = $date2;
        $date2 = date('Y-m-d', strtotime($date2. ' +1 day'));

        $result['oldorders'] = DB::table('orders')
        ->where('date', '<', $date)
        ->where('user_id',$id)
        ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('name')->where('status','approved') 
        ->get();

        $result['oldpayments'] = DB::table('payments')
        ->where(['deleted'=>NULL])
        ->where('date', '<', $date)
        ->where('user_id',$id)
        ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
        ->get();

        $result['oldslr'] = DB::table('salesreturns')
           ->where('user_id', $id)
           ->where('date', '<', $date)
           ->selectRaw('*, SUM(quantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('name') 
           ->get();
           
       $result['oldexp'] = DB::table('expenses')
           ->where('user_id', $id)
           ->where('date', '<', $date)
           ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
           ->get();

           $result['cuorsum'] = DB::table('orders')
           ->where(['save'=>NULL])
           ->where('user_id', $id)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('name')->where('status','approved') 
           ->get();

           $result['cupysum'] = DB::table('payments')
           ->where('deleted',NULL)
           ->where('user_id', $id)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
           ->get();

           $result['cuslrsum'] = DB::table('salesreturns')
           ->where('user_id', $id)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(quantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('name') 
           ->get();
           
            $result['cuexsum'] = DB::table('expenses')
           ->where('user_id', $id)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
           ->get();

        $orders = DB::table('orders')
        ->where(['save'=>null])
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->where('status','approved')
        ->where('user_id',$id)
        ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('order_id') 
        ->orderBy('orders.date','desc')
        ->get();

        $payments = DB::table('payments')->where('user_id', $id)
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->where('deleted',NULL)->get();

        $slrs = DB::table('salesreturns')
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->selectRaw('*, SUM(quantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('returnid')->where('user_id',$id) 
        ->orderBy('date','desc')
        ->get();
        
        $exp = DB::table('expenses')
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->selectRaw('*, SUM(amount) as sum')->where('user_id', $id)
        ->orderBy('date', 'desc')
        ->get();

        $data = array();
        foreach($orders as $item){
            if($item->name == NULL){

            }
            else{
            $data[] = [
                'name'=>$item->name,
                'created'=>$item->date,
                'ent_id'=>$item->order_id,
                'debit'=>$item->sum,
                'nar'=>$item->remarks,
                'vou'=>'',
                'credit'=>'0',
                'type'=>'sale',
            ];}
        }
        foreach($payments as $item){
            if($item->name == NULL){

            }
            else{
            $data[] = [
                'name'=>$item->name,
                'created'=>$item->date,
                'ent_id'=>$item->paymentid,
                'id'=>$item->id,
                'debit'=>'0',
                'nar'=>'',
                'vou'=>$item->voucher,
                'credit'=>$item->amount,
                'type'=>$item->type,
            ];}
        }
        foreach($slrs as $item){
            if($item->name == NULL){

            }
            else{
            $data[] = [
                'name'=>$item->name,
                'created'=>$item->date,
                'ent_id'=>$item->returnid,
                'debit'=>'0',
                'nar'=>'',
                'vou'=>'',
                'credit'=>$item->sum,
                'type'=>'Sales Return',
            ];}
        }
        foreach($exp as $item){
            if($item->name == NULL){

            }
            else{
                $data[] = [
                    'name'=>$item->name,
                    'created'=>$item->date,
                    'ent_id'=>$item->expenseid,
                    'id'=>$item->id,
                    'debit'=>$item->amount,
                    'nar'=>'',
                    'vou'=>$item->particular,
                    'credit'=>'0',
                    'type'=>'expense',
                ];
            }   
        }
            usort($data, function($a, $b) {
                return strtotime($a['created']) - strtotime($b['created']);
            });

        $result['data'] = collect($data);
        return view('admin/balancesheet', $result);
    }
    public function mainanalytics(Request $request){
        if($request->get('date') && $request->get('date2'))
        {
            $date = $request->get('date');
            $date2 = $request->get('date2');
        }
        elseif($request->get('date')){
            $date = $request->get('date');
            $date3 = date('Y-09-17');
            $date2 = date('Y-m-d', strtotime($date3. ' + 1 year -1 day'));
        }
        elseif($request->get('date2')){
            $date2 = $request->get('date2');
            $date = date('Y-09-17');
        }
        elseif($request->get('clear')){
             if(date('Y-m-d') < date('Y-09-17') ){
             $date2 = date('Y-09-16');  
             $date = date('Y-m-d', strtotime($date2. ' -1 year +1 day'));
            }
            else{
                $date = date('Y-09-17');
                $date2 = date('Y-m-d', strtotime($date. ' + 1 year -1 day'));
            }
        }
        else{
            if(date('Y-m-d') < date('Y-09-17') ){
             $date2 = date('Y-09-16');  
             $date = date('Y-m-d', strtotime($date2. ' -1 year +1 day'));
            }
            else{
                $date = date('Y-09-17');
                $date2 = date('Y-m-d', strtotime($date. ' + 1 year -1 day'));
            }
            
        }
        $result['date'] = $date;
        $result['date2'] = $date2;
        $date2 = date('Y-m-d', strtotime($date2. ' +1 day'));

        $result['totalsales'] = DB::table('orders')
        ->where(['deleted_at'=>NULL, 'save'=>NULL, 'orders.net'=>NULL])
        ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-1'])
        ->where('orders.date', '>=', $date)
        ->where('orders.date', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as samt')
        ->groupBy('deleted_at')
        ->get();
        
        $result['catsales'] = DB::table('orders')
        ->where(['deleted_at'=>NULL, 'save'=>NULL, 'orders.net'=>NULL])
        ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-1'])
        ->where('orders.date', '>=', $date)
        ->where('orders.date', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as samt')
        ->groupBy('brand')
        ->orderBy('samt','DESC')
        ->get();

        $result['categoryg'] = DB::table('orders')
        ->where(['deleted_at'=>NULL, 'save'=>NULL, 'orders.net'=>NULL])
        ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-1'])
        ->where('orders.date', '>=', $date)
        ->where('orders.date', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as samt')
        ->groupBy('category_id')
        ->orderBy('samt','DESC')
        ->get();

        foreach($result['catsales'] as $item){
            $result['data'][$item->brand] = DB::table('products')
            ->where(['orders.brand'=>$item->brand,'status'=>'approved','orders.deleted_at'=>NULL, 'save'=>NULL, 'orders.net'=>NULL])
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)
            ->where(function ($query) use ($request){
                if($request->get('name')){
                    $query->where('orders.name', $request->get('name'));
                }
            })
            ->join('orders', 'products.id', '=', 'orders.product_id')
            ->selectRaw('*, SUM(approvedquantity) as sum,SUM(approvedquantity * orders.price * (1-discount * 0.01) * (1-0.01*sdis)) as samt')->groupBy('orders.product_id')->orderBy('sum','desc')
            ->get();
            $result['data2'][$item->brand] = DB::table('products')
            ->where(['brand'=>$item->brand])
            ->whereNotIn('id', DB::table('orders')
            ->where(['brand'=>$item->brand,'status'=>'approved','deleted_at'=>NULL, 'save'=>NULL, 'orders.net'=>NULL])
            ->where('date', '>=', $date)
            ->where('date', '<=', $date2)
            ->where(function ($query) use ($request){
                if($request->get('name')){
                    $query->where('orders.name', $request->get('name'));
                }
            })
            ->pluck('product_id')
            ->toArray())
            ->get();
        }

        if($request->get('name')){
            $result['name'] = $request->get('name');
        }
        else{
            $result['name'] = "";
        }



        return view('admin/mainanalytics', $result);
    }
    public function sortanalytics(Request $request){
        if($request->get('date') && $request->get('date2'))
        {
            $date = $request->get('date');
            $date2 = $request->get('date2');
        }
        elseif($request->get('date')){
            $date = $request->get('date');
            $date3 = date('Y-09-17');
            $date2 = date('Y-m-d', strtotime($date3. ' + 1 year -1 day'));
        }
        elseif($request->get('date2')){
            $date2 = $request->get('date2');
            $date = date('Y-09-17');
        }
        elseif($request->get('clear')){
             if(date('Y-m-d') < date('Y-09-17') ){
             $date2 = date('Y-09-16');  
             $date = date('Y-m-d', strtotime($date2. ' -1 year +1 day'));
            }
            else{
                $date = date('Y-09-17');
                $date2 = date('Y-m-d', strtotime($date. ' + 1 year -1 day'));
            }
        }
        else{
            if(date('Y-m-d') < date('Y-09-17') ){
             $date2 = date('Y-09-16');  
             $date = date('Y-m-d', strtotime($date2. ' -1 year +1 day'));
            }
            else{
                $date = date('Y-09-17');
                $date2 = date('Y-m-d', strtotime($date. ' + 1 year -1 day'));
            }
            
        }
        $result['date'] = $date;
        $result['date2'] = $date2;
        $date2 = date('Y-m-d', strtotime($date2. ' +1 day'));

        if ($request->get('name') && $request->get('product')) {
            $result['npdata'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'save'=>NULL])
            ->where('status', 'approved')
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)
            ->where('orders.name', $request->get('name'))
            ->where('orders.item', $request->get('product'))
            ->orderBy('date', 'DESC')
            ->get();

            $result['nptotal'] =  DB::table('orders')
            ->where(['deleted_at'=>NULL, 'save'=>NULL])
            ->where('status', 'approved')
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)
            ->where('orders.name', $request->get('name'))
            ->where('orders.item', $request->get('product'))
            ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as samt')
            ->groupBy('name')
            ->get();

            $result['datatype'] = 'np';
            $result['name'] = $request->get('name');
            $result['product'] = $request->get('product');
        }
        elseif ($request->get('name')) {
            $result['totalsales'] = DB::table('orders')
        ->where(['deleted_at'=>NULL, 'save'=>NULL, 'orders.net'=>NULL])
        ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-1'])
        ->where('orders.date', '>=', $date)
        ->where('orders.date', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as samt')
        ->groupBy('deleted_at')
        ->get();
        
        $result['catsales'] = DB::table('orders')
        ->where(['deleted_at'=>NULL, 'save'=>NULL, 'orders.net'=>NULL])
        ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-1'])
        ->where('orders.date', '>=', $date)
        ->where('orders.date', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as samt')
        ->groupBy('brand')
        ->orderBy('samt','DESC')
        ->get();

        foreach($result['catsales'] as $item){
            $result['data'][$item->brand] = DB::table('products')
            ->where(['orders.brand'=>$item->brand,'status'=>'approved','orders.deleted_at'=>NULL, 'save'=>NULL, 'orders.net'=>NULL])
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)
            ->where(function ($query) use ($request){
                if($request->get('name')){
                    $query->where('orders.name', $request->get('name'));
                }
            })
            ->join('orders', 'products.id', '=', 'orders.product_id')
            ->selectRaw('*, SUM(approvedquantity) as sum,SUM(approvedquantity * orders.price * (1-discount * 0.01) * (1-0.01*sdis)) as samt')->groupBy('orders.product_id')->orderBy('sum','desc')
            ->get();
            $result['data2'][$item->brand] = DB::table('products')
            ->where(['brand'=>$item->brand])
            ->whereNotIn('id', DB::table('orders')
            ->where(['brand'=>$item->brand,'status'=>'approved','deleted_at'=>NULL, 'save'=>NULL, 'orders.net'=>NULL])
            ->where('date', '>=', $date)
            ->where('date', '<=', $date2)
            ->where(function ($query) use ($request){
                if($request->get('name')){
                    $query->where('orders.name', $request->get('name'));
                }
            })
            ->pluck('product_id')
            ->toArray())
            ->get();
        }
            $result['datatype'] = 'n';
            $result['name'] = $request->get('name');
            $result['product'] = '';
        }
        elseif ($request->get('product')){
            $result['pdata'] = DB::table('orders')
            ->where('status', 'approved')
            ->where(['orders.deleted_at'=>NULL, 'save'=>NULL])
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)
            ->where('orders.item', $request->get('product'))
            ->selectRaw('orders.*, customers.type, customers.contact, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price * (1-discount * 0.01) * (1-0.01*sdis)) as samt')
            ->orderBy('sum','desc')
            ->join('customers',  'orders.user_id', '=', 'customers.id')
            // ->select('orders.*', 'customers.type')
            ->groupBy('orders.name')
            ->get();
            $result['ptotal'] =  DB::table('orders')
            ->where('status', 'approved')
            ->where(['deleted_at'=>NULL, 'save'=>NULL])
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)
            ->where('orders.item', $request->get('product'))
            ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * orders.price * (1-discount * 0.01) * (1-0.01*sdis)) as samt')
            ->groupBy('item')
            ->get();
            $result['pnodata'] = DB::table('customers')
            ->whereNotIn('name', DB::table('orders')
                            ->where('status', 'approved')
                            ->where(['deleted_at'=>NULL, 'save'=>NULL])
                            ->where('orders.date', '>=', $date)
                            ->where('orders.date', '<=', $date2)
                            ->where('orders.item', $request->get('product'))
                            ->selectRaw('*, SUM(approvedquantity) as sum,SUM(approvedquantity * orders.price * (1-discount * 0.01) * (1-0.01*sdis)) as samt')->orderBy('sum','desc')
                            ->groupBy('orders.name')->pluck('name')->toArray())
            ->get();

            $result['datatype'] = 'p';
            $result['name'] = '';
            $result['product'] = $request->get('product');
        }
        else{
            $result['datatype'] = 'nodata';
            $result['name'] = '';
            $result['product'] = '';
        }

        return view('admin/sortanalytics', $result);
    }
    public function detailedreport(Request $request){
        $year = getNepaliYear(today());

        if($request->get('startyear')){
            $result['syear'] = $request->get('startyear');
        }
        else
        {
            $result['syear'] = $year;
        }
        if($request->get('endyear')){
            $result['eyear'] = $request->get('endyear');
        }
        else
        {
            $result['eyear'] = $year;
        }
        if($request->get('startmonth')){
            $result['smonth'] = $request->get('startmonth');
        }
        else
        {
            $result['smonth'] = "1";
        }
        if($request->get('endmonth')){
            $result['emonth'] = $request->get('endmonth');
        }
        else
        {
            $result['emonth'] = getNepaliMonth(today());
        }
        $date = getEnglishDate($result['syear'] ,  $result['smonth'], 1);
        $date2 = getEnglishDate($result['eyear'] , $result['emonth'],getLastDate($result['emonth'] , date('y', strtotime($result['eyear'] ))));
        if($request->get('name')){
            $result['data'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'status'=>'approved','net'=>NULL ,'save'=>NULL, 'name'=>$request->get('name')])
            ->orderBy('date', 'ASC')
            ->selectRaw('*,SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)  
            ->groupBy(['nepmonth', 'nepyear'])
            ->get();

            $result['fquat'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'status'=>'approved', 'net'=>NULL,'save'=>NULL, 'name'=>$request->get('name')])
            ->whereIn('nepmonth', [1,2,3])
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)  
            ->orderBy('date', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')
            ->groupBy('nepyear')
            ->get();
        
            $result['squat'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'status'=>'approved', 'net'=>NULL,'save'=>NULL, 'name'=>$request->get('name')])
            ->whereIn('nepmonth', [4,5,6])
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)  
            ->orderBy('date', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')
            ->groupBy('nepyear')
            ->get();

            $result['tquat'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'status'=>'approved', 'net'=>NULL,'save'=>NULL, 'name'=>$request->get('name')])
            ->whereIn('nepmonth', [7,8,9])
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)  
            ->orderBy('date', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')
            ->groupBy('nepyear')
            ->get();

            $result['frquat'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'status'=>'approved','net'=>NULL,'save'=>NULL, 'name'=>$request->get('name')])
            ->whereIn('nepmonth', [10,11,12])
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)  
            ->orderBy('date', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')
            ->groupBy('nepyear')
            ->get();

            $result['fifdays'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'net'=>NULL])
            ->where('name', $request->get('name'))
            ->whereBetween('date', [now()->subDays(15), now()])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')->groupBy('name')
            ->get();

            $result['custdata'] = DB::table('customers')->where('name', $request->get('name'))->first();

            $result['thirdays'] = DB::table('orders')
            ->where(['deleted_at'=>NULL,'net'=>NULL,'save'=>NULL])
            ->where('name', $request->get('name'))
            ->where('status','approved') 
            ->whereBetween('date', [now()->subDays(15), now()->addDays(1)])
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')->groupBy('name')
            ->get();

            $result['fourdays'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'net'=>NULL])
            ->where('name',$request->get('name'))
            ->whereBetween('date', [now()->subDays(25), now()->addDays(1)])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')->groupBy('name')
            ->get();

            $result['sixdays'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'net'=>NULL])
            ->where('name', $request->get('name'))
            ->whereBetween('date', [now()->subDays(35), now()->addDays(1)])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')->groupBy('name')
            ->get();
            $result['nindays'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'net'=>NULL])
            ->where('name', $request->get('name'))
            ->whereBetween('date', [now()->subDays(45), now()->addDays(1)])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')->groupBy('name')
            ->get();
            
            $result['custs'] = 'no data';
            $result['name'] = $request->get('name');
        }
        else{
            $result['data'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'net'=>NULL,'status'=>'approved', 'save'=>NULL])
            ->orderBy('date', 'ASC')
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)  
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')
            ->groupBy(['nepmonth', 'nepyear'])
            ->get();

            $result['fquat'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'net'=>NULL, 'status'=>'approved', 'save'=>NULL])
            ->whereIn('nepmonth', [1,2,3])
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)  
            ->orderBy('date', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')
            ->groupBy('nepyear')
            ->get();

            $result['squat'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'net'=>NULL, 'status'=>'approved', 'save'=>NULL])
            ->whereIn('nepmonth', [4,5,6])
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)  
            ->orderBy('date', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')
            ->groupBy('nepyear')
            ->get();

            $result['tquat'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'net'=>NULL, 'status'=>'approved', 'save'=>NULL])
            ->whereIn('nepmonth', [7,8,9])
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)  
            ->orderBy('date', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')
            ->groupBy('nepyear')
            ->get();

            $result['frquat'] = DB::table('orders')
            ->where(['deleted_at'=>NULL, 'net'=>NULL, 'status'=>'approved', 'save'=>NULL])
            ->whereIn('nepmonth', [10,11,12])
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)  
            ->orderBy('date', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sl')
            ->groupBy('nepyear')
            ->get();

            $date = getEnglishDate($result['syear'] ,  $result['smonth'], 1);
            $date2 = getEnglishDate($result['eyear'] , $result['emonth'],getLastDate($result['emonth'] , date('y', strtotime($result['eyear'] ))));

            $result['tss'] = DB::table('orders')
            ->where('deleted_at', NULL)->where('save', NULL)->where('status', 'approved')->where('net', NULL) 
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)  
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')
            ->groupBy('deleted_at')
            ->get();

            $result['custs'] = DB::table('orders')->where('orders.deleted_at', NULL)->where('save', NULL)->where('status', 'approved')->where('net', NULL) 
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)  
            ->selectRaw('orders.*, customers.type, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')
            ->groupBy('orders.name')
            ->join('customers', 'orders.user_id', '=', 'customers.id')
            ->orderBy('sum', 'DESC')
            ->get();

            $result['cusnts'] = DB::table('customers')->whereNotIn('name', DB::table('orders')->where('deleted_at', NULL)->where('save', NULL)->where('status', 'approved') ->where('net', NULL)
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)  
            ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('name')->pluck('name')->toArray())
            ->get();


            $result['name'] = "";
        }      
        return view ('admin/summary', $result);
    }

}
