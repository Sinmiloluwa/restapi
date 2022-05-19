<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AdminAuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User Authentication
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

// Admin Authentication
Route::prefix('admin')->group(function () {
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::post('/login', [AdminAuthController::class, 'login']);
});
//user routes
Route::group(['middleware' => 'auth:sanctum'], function () {
// The route that the button calls to initialize payment
    Route::post('/pay', [PaymentController::class, 'initialize'])->name('pay');
// The webhook url after a payment
// Using flutterwave webhook test url for the sake of this project
    Route::post('/webhook/flutterwave', [PaymentController::class, 'webhook'])->name('webhook');
});

Route::group(['prefix'=>'agent','middleware'=>'agent'], function () {

});

Route::group(['prefix'=>'admin','middleware'=>'admin'], function () {

});
