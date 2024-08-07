<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishListController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CurrencyController;
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
use App\Http\Controllers\UserController;

Route::get('/chat-users', [UserController::class, 'index']);

Route::get('/changecurrency', [CurrencyController::class, 'changeCurrency'])->name('home.changecurrency');


Route::get('/', [HomeController::class, 'HomeInfo'])->name('home.home')->middleware('auth');
Route::get('/stores', [StoreController::class, 'getAllApprovedStores'])->name('store.home');
Route::get('/stores/search', [StoreController::class, 'getStoresLike'])->name('store.filtered');
Route::get('/stores/{store_id}', [StoreController::class, 'getProducts'])->name('store.view');
Route::get('/stores/{store_id}/search', [StoreController::class, 'getProductsLike'])->name('product.filtered');
Route::get('/stores/{store_id}/products/{product_id}', [StoreController::class, 'productDetails'])->name('home.viewProduct');
Route::post('/stores/{store_id}/products/{product_id}/addToCart', [StoreController::class, 'addProductToCart'])->name('user.addToCart');
Route::get('/cart/{id}', [StoreController::class, 'removeProductFromCart'])->name('user.removeFromCart');
Route::get('/cart', [StoreController::class, 'getCart'])->name('user.viewCart')->middleware('auth');
Route::get('/invites', [StoreController::class, 'getInvites'])->name('user.viewInvites')->middleware('auth');
Route::get('/invites/{id}/accept', [StoreController::class, 'acceptInvite'])->name('home.acceptInvite')->middleware('auth');
Route::get('/invites/{id}/decline', [StoreController::class, 'declineInvite'])->name('home.declineInvite')->middleware('auth');
Route::post('/auction/{id}', [StoreController::class, 'placeBid'])->name('home.placeBid')->middleware('auth');
Route::get('/auction/{id}', [StoreController::class, 'auctionEvent'])->name('home.enterAuction')->middleware('auth');
Route::get('/auction/{id}/close', [StoreController::class, 'closeAuction'])->name('home.closeAuction')->middleware('checkSeller');
Route::get('/auction', [StoreController::class, 'getUserAuction'])->name('home.getUserAuctions')->middleware('auth');


Route::post('/cart/stripe', [StoreController::class, 'stripe'])->name('home.stripePayement')->middleware('auth');
Route::post('/cart/stripe/submit', [StoreController::class,'stripePost'])->name('home.stripePayementSubmit');
Route::post('/cart/cashondelivery', [StoreController::class,'cashOnDelivery'])->name('home.cashOnDeliverySubmit');

Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirect']);

Route::get('/auth/{provider}/callback', [AuthController::class, 'callback']);

use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Incoming\Answer;
Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
// Route::match(['get', 'post'], '/botman', 'BotManController@handle');

Route::get('/order-address', function () {
    return view('order-address');
})->name('order-address');



Route::get('/seller-order-address', function () {
    return view('seller-order-address');
});

Route::get('/chat', 'App\Http\Controllers\PusherController@index');
// Route::get('/users', 'PusherController@showUserList');
Route::get('/chat/{user}', 'App\Http\Controllers\PusherController@startChatWithUser');

Route::post('/broadcast','App\Http\Controllers\PusherController@broadcast');
Route::post('/receive','App\Http\Controllers\PusherController@receive');
// Route::get('/user-list', 'PusherController@showUserList')->middleware('auth');
// Route::get('/start-chat/{user}', 'PusherController@startChatWithUser')->middleware('auth');


Route::get('/user-list', 'App\Http\Controllers\PusherController@showUserList');
Route::get('/start-chat/{user}', 'App\Http\Controllers\PusherController@startChatWithUser');


// Route::get('/users', [UserController::class, 'index']);
Route::get('/chat/{user}', [UserController::class, 'startChat'])->name('startChat');


Route::post('/store-message', [UserController::class, 'storeMessage']);
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PusherController;

Route::get('/fetch-messages/{userId}', [UserController::class, 'fetchMessages']);

////////////////////////////

use App\Http\Controllers\SellerController;
use App\Models\Product;
use App\Models\Store;

Route::get('/sellers/{seller}', [SellerController::class, 'show'])->name('sellers.show')->middleware('checkSeller');
Route::get('/sellers/{seller}/createStore', [SellerController::class, 'showAddStoreForm'])->name('sellers.createStore')->middleware('checkSeller');
Route::post('/sellers/{seller}/createStore', [StoreController::class, 'addStore'])->name('sellers.submitCreateStore')->middleware('checkSeller');
Route::get('/sellers/{seller}/editStore/{store}', [SellerController::class, 'showEditStoreForm'])->name('sellers.editStore')->middleware('checkSeller');
Route::put('/sellers/{seller}/editStore/{store}', [SellerController::class, 'updateStore'])->name('sellers.submitEditStore')->middleware('checkSeller');
Route::get('/sellers/{seller}/editStore/{store}/createCategory', [SellerController::class, 'showAddCategoryForm'])->name('sellers.createCategory')->middleware('checkSeller');
Route::post('/sellers/{seller}/editStore/{store}/createCategory', [StoreController::class, 'addCategory'])->name('sellers.submitCreateCategory')->middleware('checkSeller');
Route::get('/sellers/{seller}/editStore/{store}/deleteCategory/{category}', [StoreController::class, 'deleteCategory'])->name('sellers.deleteCategory')->middleware('checkSeller');
Route::get('/sellers/{seller}/editStore/{store}/createProdcut', [SellerController::class, 'showAddProductForm'])->name('sellers.createProduct')->middleware('checkSeller');
Route::post('/sellers/{seller}/editStore/{store}/createProduct', [StoreController::class, 'addProduct'])->name('sellers.submitCreateProduct')->middleware('checkSeller');
Route::get('/sellers/{seller}/editStore/{store}/deleteProduct/{product}', [StoreController::class, 'deleteProduct'])->name('sellers.deleteProduct')->middleware('checkSeller');
Route::get('/sellers/{seller}/editStore/{store}/editproduct/{product}', [SellerController::class, 'showEditProductForm'])->name('sellers.editProduct')->middleware('checkSeller');
Route::put('/sellers/{seller}/editStore/{store}/editproduct/{product}', [StoreController::class, 'editProduct'])->name('sellers.submitEditProduct')->middleware('checkSeller');
Route::delete('/sellers/{seller}/editStore/{store}/deleteAuction/{auction}', [StoreController::class, 'deleteAuction'])->name('sellers.deleteAuction')->middleware('checkSeller');
Route::get('/sellers/{seller}/editStore/{store}/auctions/{auction}', [StoreController::class, 'getAuctions'])->name('sellers.auctionDetails')->middleware('checkSeller');
Route::put('/sellers/{seller}/editStore/{store}/auctions/{auction}/update', [StoreController::class, 'updateAuctions'])->name('sellers.updateAuction')->middleware('checkSeller');
Route::get('/sellers/{seller}/editStore/{store}/auctions/{auction}/invite', [SellerController::class, 'showInviteBuyerToAuctionForm'])->name('sellers.inviteBuyerToAuction')->middleware('checkSeller');
Route::post('/sellers/{seller}/editStore/{store}/auctions/{auction}/invite', [SellerController::class, 'inviteBuyerToAuction'])->name('sellers.submitInviteBuyerToAuction')->middleware('checkSeller');
Route::get('/sellers/order/{id}', [SellerController::class, 'viewOrder'])->name('sellers.viewOrder')->middleware('checkSeller');
Route::get('/sellers/order/{id}/changeOrderStatus', [SellerController::class, 'changeOrderStatus'])->name('sellers.changeOrderStatus')->middleware('checkSeller');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', function () {
        $stores = Store::take(3)->get();
        $products = Product::take(3)->get();

        return view('home.home')->with(['all_stores' => $stores])->with(['all_products' => $products]);
    })->name('home.home');
});




Route::get('/pusher', function(){return view('pusher');})->name('chat.users');

Route::get('/pusher2', function(){return view('pusher2');});



Route::get('/sendPusher', [PusherController::class,"sendPusher"]);
Route::post('/sendPusher', [PusherController::class, "sendPusher"]);

Route::middleware('admin')->group(function () {
    Route::get('/admin', [AdminController::class, "index"])->name('admin.index');
    Route::get('/admin/pending', [AdminController::class, "pendingStores"])->name('admin.pendingStores');
    Route::post('/admin/store/{id}/accept', [AdminController::class, "acceptStore"])->name('admin.acceptStore');
    Route::post('/admin/store/{id}/reject', [AdminController::class, "rejectStore"])->name('admin.rejectStore');
    Route::put('/admin/store/{store}', [AdminController::class, "toggleStoreStatus"])->name('admin.toggleStoreStatus');
    Route::put('/admin/store/{store}', [AdminController::class, "toggleStoreStatus"])->name('admin.toggleStoreStatus');
    Route::get('/admin/store/{store}/edit', [SellerController::class, 'editStore'])->name('admin.editStore');
    //  Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/reset-user-password/{user}', [AdminController::class, 'resetUserPassword'])->name('admin.resetUserPassword');
    Route::put('/admin/store/{store}', [AdminController::class, 'updateStore'])->name('admin.updateStore');
    Route::put('/admin/stores/{store}', [AdminController::class, 'toggleStoreStatus'])->name('admin.toggleStoreStatus');
    Route::get('/admin/users', [UserController::class, 'getAllUsers'])->name('admin');
    Route::post('/admin/users/reset-password/{user}', [AdminController::class, 'resetPassword'])->name('admin.resetPassword');
});

Route::get('/getBuyerOrders', [OrderController::class, "getBuyerOrders"])->name('home.getBuyerOrders');

Route::post('/stores/{store_id}/products/{product_id}/addToWishlist', [WishListController::class, 'addProductToWishlist'])->name('user.addToWishlist');
Route::get('/wishlist/{id}', [WishListController::class, 'removeProductFromWishlist'])->name('user.removeFromWishlist');
Route::get('/wishlist', [WishListController::class, 'getWishlist'])->name('user.viewWishlist')->middleware('auth');
