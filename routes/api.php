<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FrontController;

Route::group(['middleware'=>'api_key'], function () { 

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/products', [ProductController::class, 'getproduct']);
    Route::get('/products2', [ProductController::class, 'getproduct2']);
    Route::get('/product/{id}', [ProductController::class, 'getproductdetail']);
    
    Route::get('/brands', [BrandController::class, 'getBrands']);
    Route::get('/category', [CategoryController::class, 'getCategoryApi']);
    
    Route::get('/maxPrice', [ProductController::class, 'maxPrice']);
    
    Route::get('/sliderimgs', [FrontController::class, 'sliderimgs']);
    
    Route::get('maxDiscount', [ProductController::class, 'maxDiscount']);
    
    Route::get('terms', [FrontController::class, 'getTerms']);
    Route::get('policy', [FrontController::class, 'getPolicy']);
    
    Route::post('/forgotpwd', [AuthController::class, 'sendResetLinkEmail']);
    Route::post('/resetpwd/validatecredentials', [AuthController::class, 'rp_validateCreds']);
    Route::post('/resetpwd/newpwd', [AuthController::class, 'set_newpass']);

    Route::get('/homecategory', [CategoryController::class, 'homeCategory']);

    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

    Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->name('verification.resend');

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });
});



