<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerViewController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderAdminController;
use App\Http\Controllers\ChalanController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\MarketerViewController;
use App\Http\Controllers\MarketerController;
use App\Http\Controllers\TrashController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AdminController::class, 'login']);
// Route::get('/adduser', [AdminController::class, 'superuser']);

Route::post('/auth', [LoginController::class, 'auth'])->name('auth');

Route::get('/logout', function(){
    session()->flush();
    session()->flash('error','Logged Out');
    return redirect('/');
});

Route::group(['middleware'=>'AdminAuth'], function(){
    Route::get('/dashboard', [LoginController::class, 'dashboard']);

    Route::get('/admin/profile', [AdminController::class, 'profile']);
    Route::post('/admin/changepassword', [AdminController::class, 'changepassword']);

    //Admins
    Route::get('/admins', [AdminController::class, 'admins']);
    //AJAX ADMIN
    Route::get('/admin/getdata/{id}', [AdminController::class, 'getadmin']);
    Route::get('/admin/getadmindata', [AdminController::class, 'getadmindata']);
    Route::post('/admin/editadmin', [AdminController::class, 'editadmin']);
    Route::post('/admin/addadmin', [AdminController::class, 'addadmin']);
    Route::get('/admin/deladmin/{id}', [AdminController::Class, 'deladmin']);

    //Customer
    Route::get('/customers', [CustomerController::class, 'customers']);
    Route::get('/customers/add', [CustomerController::class, 'addcustomer']);
    Route::post('/customers/addpro', [CustomerController::class, 'addcus_process'])->name('addcust');
    Route::get('/customers/edit/{id}', [CustomerController::class, 'editcustomer']);
    Route::post('customers/editcus', [CustomerController::class, 'editcus_process'])->name('editcust');
    Route::get('/customers/delcust/{id}', [CustomerController::class, 'deletecustomer']); //soft

     //FRONT SETTINGS
     Route::get('frontsettings', [FrontController::class, 'index']);
     Route::post('frontimg', [FrontController::class, 'addimg'])->name('addimg');
     Route::get('delete/frontimg/{id}/{id2}', [FrontController::class, 'deleteimg']);
     Route::post('frontmsg', [FrontController::class, 'addmsg'])->name('addmsg');
     Route::get('delete/frontmsg/{id}', [FrontController::class, 'deletemsg']);

     //Product
     Route::get('/products', [ProductController::class, 'products']);
     Route::get('/products/add', [ProductController::class, 'addproduct']);
     Route::post('/products/addpro', [ProductController::class, 'addprod_process'])->name('addprod');
     Route::get('/products/edit/{id}', [ProductController::class, 'editproduct']);
     Route::post('products/editprod', [ProductController::class, 'editprod_process'])->name('editprod');
     Route::get('/products/delprod/{id}', [ProductController::class, 'deleteproduct']); //soft
     Route::post('/product/updatearrangement', [ProductController::class, 'arrange']);
    //  Route::get("/addmp", [ProductController::class, 'addmp']);

    //brands
    Route::get('/brands', [BrandController::class, 'index']);
    //AJAX BRANDS
    Route::get('/brand/getdata/{id}', [BrandController::class, 'getbrand']);
    Route::get('/brand/getbranddata', [BrandController::class, 'getbranddata']);
    Route::post('/brand/editbrand', [BrandController::class, 'editbrand']);
    Route::post('/brand/addbrand', [BrandController::class, 'addbrand']);
    Route::get('/brand/delbrand/{id}', [BrandController::Class, 'delbrand']);

    //category
    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/category/getdata/{id}', [CategoryController::class, 'getcategory']);
    Route::get('/category/getcatdata', [CategoryController::class, 'getcategorydata']);
    Route::post('/category/editcat', [CategoryController::class, 'editcategory']);
    Route::post('/category/addcat', [CategoryController::class, 'addcategory']);
    Route::get('/category/delcat/{id}', [CategoryController::Class, 'delcategory']);
    Route::post('/category/updatearrangement', [CategoryController::class, 'arrange']);

    Route::get('/terms/edit', [FrontController::class, 'editterms']);
    Route::post('/terms/edit/process', [FrontController::class, 'editterms_process'])->name('editterms');

    Route::get('/policy/edit', [FrontController::class, 'editpolicy']);
    Route::post('/policy/edit/process', [FrontController::class, 'editpolicy_process'])->name('editpolicy');

    //Payments CRUD
    Route::get('payments', [PaymentController::class, 'index']);
    Route::get('addpayment', [PaymentController::class, 'addpay']);
    Route::get('editpayment/{id}', [PaymentController::class, 'addpay']);
    Route::post('addpay', [PaymentController::class, 'addpay_process'])->name('addpay');
    Route::get('deletepayment/{id}',[PaymentController::class, 'deletepay']); //soft

    //ORDER VIEW PAGES
    Route::get('orders', [OrderAdminController::class, 'orders']);
    Route::get('approvedorders', [OrderAdminController::class, 'approvedorders']);
    Route::get('pendingorders', [OrderAdminController::class, 'pendingorders']);
    Route::get('rejectedorders', [OrderAdminController::class, 'rejectedorders']);
    Route::get('deliveredorders', [OrderAdminController::class, 'deliveredorders']);

    Route::get('/detail/{id}', [OrderAdminController::class, 'details']);

    Route::get('/addorder', [OrderAdminController::class, 'addorder']);
    Route::post('/addorder/add', [OrderAdminController::class, 'createorder'])->name("createorder");
    Route::get('/editorder/{id}', [OrderAdminController::class, 'editorder']);
    Route::post('/order/edit', [OrderAdminController::class, 'editorder_process'])->name("editorder");

    Route::post('/detailupdate', [OrderAdminController::class, 'detailupdate'])->name('detailupdate');
    Route::post('seenupdate', [OrderAdminController::class, 'seenupdate']);
    Route::post('updatecln', [ChalanController::class, 'updatechalan']);
    Route::post('updatedeliver', [OrderAdminController::class, 'updatedeliver']);

    Route::get('/deleteorder/{id}', [OrderAdminController::class, 'deleteorder']); //soft

    //CHALAN PAGES
    Route::get('chalan', [ChalanController::class, 'chalan']);
    Route::get('chalandetail/{id}', [ChalanController::class, 'chalandetail']);

    //PRINT ORDERS
    Route::get('saveorder/{id}', [OrderAdminController::class, 'save']);
    Route::get('printorder/{id}', [OrderAdminController::class, 'print']);

    Route::get('bulkprintorders', [OrderAdminController::class, 'bprintindex']);
    Route::post('bulkprint', [OrderAdminController::class, 'bulkprint'])->name('bulkprint');

    Route::get('/printcat/{id}/{id2}', [HomeController::class, 'printcat']);

     //expenses CRUD
     Route::get('expenses', [ExpenseController::class, 'index']);
     Route::get('addexpense', [ExpenseController::class, 'addexp']);
     Route::get('editexpense/{id}', [ExpenseController::class, 'addexp']);
     Route::post('addexp', [ExpenseController::class, 'addexp_process'])->name('addexp');
     Route::get('deleteexpense/{id}',[ExpenseController::class, 'deleteexp']);

     //SALESRETURN CRUD
    Route::get('slr', [SalesReturnController::class, 'index']);
    Route::get('slrdetail/{id}',[SalesReturnController::class, 'detail']);
    Route::get('createslr', [SalesReturnController::class, 'createslr']);
    Route::post('admin/addslr', [SalesReturnController::class, 'addslr'])->name('admin.addslr');
    Route::post('admin/editslr', [SalesReturnController::class, 'editslr_process'])->name('admin.editslr');
    Route::post('admin/editslrdet', [SalesReturnController::class, 'editslrdet_process'])->name('admin.editslrdet');
    Route::get('deleteslr/{id}',[SalesReturnController::class, 'deleteslr']);
    Route::get('editslr/{id}', [SalesReturnController::class, 'editslr']);


    //Analytics
    Route::get('statements', [AnalyticsController::class,'statement']);
    Route::get('balancesheet/{id}', [AnalyticsController::class, 'balancesheet']);
    Route::get('mainanalytics', [AnalyticsController::class, 'mainanalytics']);
    Route::get('sortanalytics', [AnalyticsController::class, 'sortanalytics']);
    Route::get('summary', [AnalyticsController::class, 'detailedreport']);

    Route::get('/staff', [StaffController::class, 'staff']);
    Route::get('/addstaff', [StaffController::class, 'addstaff']);
    Route::get('/addstaff/{id}', [StaffController::class, 'addstaff']);
    Route::post('/addstaffprocess', [StaffController::class, 'addstaff_process'])->name('addstaffprocess');
    Route::get('/deletestaff', [StaffController::class, 'deletestaff']);

    Route::get('/marketer', [MarketerController::class, 'marketer']);
    Route::get('/addmarketer', [MarketerController::class, 'addmarketer']);
    Route::get('/addmarketer/{id}', [MarketerController::class, 'addmarketer']);
    Route::post('/addmarketerprocess', [MarketerController::class, 'addmarketer_process'])->name('addmarketerprocess');
    Route::get('/deletemarketer', [MarketerController::class, 'deletemarketer']);


    //TRASH
    Route::get('/trash', [TrashController::class, 'trash']);
        //restore
        Route::get('/restore/order/{id}', [TrashController::class, 'order_restore']);
        Route::get('/restore/payment/{id}', [TrashController::class, 'payment_restore']);
        //DELETE
        Route::get('/trashdel/order/{id}', [TrashController::class, 'order_delete']);
        Route::get('/trashdel/payment/{id}', [TrashController::class, 'payment_delete']);

    //Ajax gets
    Route::get('findcustomer', [CustomerController::class, 'getcustomer']);
    Route::get('finditem', [ProductController::class, 'getproduct']);

    Route::put('/admin/banner/{id}', [FrontController::class, 'updateBanner'])->name('updateBanner');
});

Route::group(['middleware'=>'CustomerAuth'], function(){

    Route::get('user/finditem', [ProductController::class, 'getproduct']);

    Route::get('/user/createorder', [HomeController::class, 'home']);
    Route::get('/user/home', [HomeController::class, 'homereal']);
  

    Route::get('/user/profile', [CustomerViewController::class, 'profile']);
    Route::post('/user/updateprofile', [CustomerViewController::class, 'updateprofile'])->name('editprofile');
    Route::get('/user/statement', [CustomerViewController::class, 'statement']);
    
    Route::post('/user/updatecart', [CartController::class, 'updatecart']);
    Route::get('/user/getcart', [CartController::class, 'getcart']);
    Route::get('/user/gettotal', [CartController::class, 'gettotal']);
    Route::get('/user/confirmcart', [OrderController::class, 'confirmcart']);
    Route::get('/user/savecart', [OrderController::class, 'savecart']);
    Route::get('/user/confirmorder/{id}', [OrderController::class, 'confirm']);

    Route::get('user/finditem/{id}', [ProductController::class, 'getproductdetail']);

    Route::get('/user/oldorders', [OrderController::class, 'oldorders']);
    Route::get('/user/savedorders', [OrderController::class, 'savedorders']);
    Route::get('/user/detail/{id}', [OrderController::class, 'detail']);
    Route::post('/user/editdetail', [OrderController::class, 'detailedit'])->name('user.detailedit');
    Route::get('/user/recieve/{id}',[OrderController::class, 'recieveorder']);

    Route::get('/user/mainanalytics',[CustomerViewController::class, 'mainanalytics']);
    Route::get('/user/summary',[CustomerViewController::class, 'summary']);

    Route::get('/user/saveorder/{id}', [OrderAdminController::class, 'save']);
    Route::get('/user/printorder/{id}', [OrderAdminController::class, 'print']);

    Route::get("/user/editorder/{id}", [OrderController::class, 'editorder']);
    Route::post('/user/order/edit', [OrderController::class, 'editorder_process'])->name("user.editorder");

    Route::get('/user/deleteorder/{id}', [OrderController::class, 'deleteorder']);
});


Route::group(['middleware'=>'MarketerAuth'], function(){
    Route::get('/marketer/dashboard', [MarketerViewController::class, 'dashboard']);


    Route::get('/marketer/payments', [MarketerViewController::class, 'index']);
    Route::get('/marketer/addpayment', [MarketerViewController::class, 'addpay']);
    Route::get('/marketer/editpayment/{id}', [MarketerViewController::class, 'addpay']);
    Route::post('/marketer/addpay', [MarketerViewController::class, 'addpay_process'])->name('marketer_addpay');
    Route::get('/marketer/deletepayment/{id}',[MarketerViewController::class, 'deletepay']);

    Route::get('/marketer/findcustomer', [MarketerViewController::class, 'getcustomer']);

    Route::get('/marketer/statements', [MarketerViewController::class,'statement']);
    Route::get('/marketer/balancesheet/{id}', [MarketerViewController::class, 'balancesheet']);

    Route::get('/marketer/detail/{id}', [MarketerViewController::class, 'details']);

    Route::get('/marketer/addorder', [MarketerViewController::class, 'addorder']);
    Route::post('/marketer/addorder/add', [MarketerViewController::class, 'createorder'])->name("marketer_createorder");
    Route::get('/marketer/editorder/{id}', [MarketerViewController::class, 'editorder']);
    Route::post('/marketer/order/edit', [MarketerViewController::class, 'editorder_process'])->name("marketer_editorder");

    Route::get('/marketer/saveorder/{id}', [OrderAdminController::class, 'save']);
    Route::get('/marketer/printorder/{id}', [OrderAdminController::class, 'print']);
});

Route::get('/express/{any}', function () {
    abort(404);
})->where('any', '.*');


