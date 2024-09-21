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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/products', [ProductController::class, 'getproduct']);
Route::get('/products2', [ProductController::class, 'getproduct2']);
Route::get('/product/{id}', [ProductController::class, 'getproductdetail']);

Route::get('/brands', [BrandController::class, 'getBrands']);
Route::get('/category', [CategoryController::class, 'getCategoryApi']);

Route::get('/maxPrice', [ProductController::class, 'maxPrice']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
