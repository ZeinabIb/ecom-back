<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
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



// user (EVERYONE)
Route::middleware('auth:sanctum')->put('/user/update', [AuthController::class, 'updatePhone']);
Route::middleware('auth:sanctum')->get('/user/getAllApprovedStores', [StoreController::class, 'getAllApprovedStores']);
Route::middleware('auth:sanctum')->get('/user/getCategoriesForStore/{store_id}', [StoreController::class, 'getCategoriesForStore']);


// seller
Route::middleware('auth:sanctum')->post('/seller/addStore', [StoreController::class, 'addStore']);
Route::middleware('auth:sanctum')->get('/seller/getAllStoresForBuyer', [StoreController::class, 'getAllStoresForBuyer']);
Route::middleware('auth:sanctum')->post('/seller/addCategory', [StoreController::class, 'addCategory']);
Route::middleware('auth:sanctum')->post('/seller/deleteCategory', [StoreController::class, 'deleteCategory']);

//buyer


// admin
Route::middleware('auth:sanctum')->post('/admin/reviewStore', [StoreController::class, 'reviewStore']);
Route::middleware('auth:sanctum')->get('/admin/getAllStores', [StoreController::class, 'getAllStores']);
