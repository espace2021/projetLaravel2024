<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategorieController;

use App\Http\Controllers\ScategorieController;

use App\Http\Controllers\ArticleController;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\StripeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('api')->group(function () {
    Route::resource('categories', CategorieController::class);
});
/*
// Route protégée
Route::group(['middleware' => ['auth:api']], function () {
    Route::resource('categories', CategorieController::class);
});
*/
Route::middleware('api')->group(function () {
    Route::resource('scategories', ScategorieController::class);        
});


Route::get('/scat/{idcat}', [ScategorieController::class,'showSCategorieByCAT']);

Route::middleware('api')->group(function () {
    Route::resource('articles', ArticleController::class);
});


Route::get('/articles/art/pagination', [ArticleController::class, 'showArticlesPagination']);

Route::get('/articles/art/paginationPaginate', [ArticleController::class, 'paginationPaginate']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'users'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refreshToken', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});
Route::get('users/verify-email', [AuthController::class, 'verifyEmail'])->name('verify.email');
/*
Route::group([
    'middleware' => 'api',
    'prefix' => 'payment'
], function ($router) {
Route::post('/processpayment', [StripeController::class, 'processpayment']);
});*/
//Route::post('/create-checkout-session', [StripeController::class, 'createCheckoutSession']);

Route::post('/payment/processpayment', [StripeController::class, 'processPayment']);