<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;

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

Route::get('/', function () {return view('home.home');});
Route::get('/stores', [StoreController::class, 'getAllApprovedStores']);
Route::get('/stores/{store_id}/', [StoreController::class, 'getProducts']);

Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirect']);

Route::get('/auth/{provider}/callback', [AuthController::class, 'callback']);


use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Incoming\Answer;
Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
// Route::match(['get', 'post'], '/botman', 'BotManController@handle');

Route::get('/order-address', function () {
    return view('order-address');
});

Route::get('/seller-order-address', function () {
    return view('seller-order-address');
});

Route::get('/chat','App\Http\Controllers\PusherController@index');

Route::post('/broadcast','App\Http\Controllers\PusherController@broadcast');
Route::post('/receive','App\Http\Controllers\PusherController@receive');
// Route::get('/user-list', 'PusherController@showUserList')->middleware('auth');
// Route::get('/start-chat/{user}', 'PusherController@startChatWithUser')->middleware('auth');


Route::get('/user-list', 'App\Http\Controllers\PusherController@showUserList');
Route::get('/start-chat/{user}', 'App\Http\Controllers\PusherController@startChatWithUser');
use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'index']);
Route::get('/chat/{user}', [UserController::class, 'startChat'])->name('startChat');



use App\Http\Controllers\AdminController;

// Route::get('/admin', [AdminController::class, 'index']);
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');


Route::put('/admin/stores/{store}', [AdminController::class, 'toggleStoreStatus'])->name('admin.toggleStoreStatus');
use App\Http\Controllers\SellerController;

Route::get('/admin/store/{store}/edit', [SellerController::class, 'editStore'])->name('admin.editStore');


Route::put('/admin/store/{store}', [AdminController::class, 'updateStore'])->name('admin.updateStore');

Route::get('/sellers/{seller}', [SellerController::class, 'show'])->name('sellers.show');

Route::post('/sellers/{seller}/reset-password', [SellerController::class, 'resetPassword'])->name('sellers.resetPassword');


