<?php

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ProductController::class, 'index'])->name('welcome');
// Route::get('/{id}', function ($id) {
//         $product = Product::where('id', $id)->first();
//         return view('welcome', compact('product'));
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::group( function () {
//     // The route that the button calls to initialize payment
     
//     // The callback url after a payment
//         Route::get('/rave/callback', [PaymentController::class, 'callback'])->name('callback');
//     });

Route::post('/pay', [PaymentController::class, 'initialize'])->name('pay');
// The callback url after a payment
Route::get('/rave/callback', [PaymentController::class, 'callback'])->name('callback');
// webhooks
Route::post('/webhook/flutterwave', [PaymentController::class, 'webhook'])->name('webhook');

