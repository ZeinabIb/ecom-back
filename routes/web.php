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

Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirect']);

Route::get('/auth/{provider}/callback', [AuthController::class, 'callback']);

use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Incoming\Answer;
Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
