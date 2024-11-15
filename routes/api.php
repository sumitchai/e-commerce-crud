<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [MemberController::class, 'login']);
Route::post('register', [MemberController::class, 'register']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function(){
    Route::get('profile',[MemberController::class, 'profile']);
});

Route::get('product', [ProductController::class, 'getProduct']);
Route::get('product/{id}', [ProductController::class, 'getProductDetail']);
Route::post('create-product', [ProductController::class, 'createProduct']);
Route::post('update-product/{id}', [ProductController::class, 'updateProduct']);
Route::delete('delete-product/{id}', [ProductController::class, 'deleteProduct']);
