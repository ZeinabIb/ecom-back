<?php

namespace App\Http\Controllers;

use App\Mail\OrderDeliveredMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;

class SellerController extends Controller
{
    public function index()
    {

        $sellers = User::where('user_type', 'seller')->get();

        return view('sellers.index', ['sellers' => $sellers]);
    }

    public function show($sellerId)
    {
        $seller = User::findOrFail(auth()->user()->id);
        $stores = Store::All()
        ->where("seller_id", auth()->user()->id);
        $orders = Order::All()
        ->where("seller_id", auth()->user()->id);
        return view('sellers.seller', ['seller' => $seller, 'stores' => $stores, 'orders' => $orders]);
    }

    public function showAddStoreForm() {
        return view('sellers.addStore', ['seller' => auth()->user()]);
    }

    public function showEditStoreForm($sellerId, $storeId) {
        $store = Store::findOrFail($storeId);


        $seller = $store->seller()->first();
        return view('sellers.edit_store', ['store' => $store, 'seller' => $seller, 'categories' => $store->categories, 'products' => $store->products, 'auctions' => $store->auctions]);
    }

    public function updateStore(Request $request, $sellerId, $storeId)
    {
        // Retrieve the store
        $store = Store::findOrFail($storeId);

        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        // Update the store details
        $store->update([
            'name' => $request->input('name'),
            'details' => $request->input('description'),
        ]);

        // Redirect back to the edit store page with a success message
        return redirect()->route('sellers.editStore', ['seller' => auth()->user()->id, 'store' => $store->id])->with('success', 'Store updated successfully!');
    }

    public function editStore(Request $request, $storeId)
    {
        try {

            $store = Store::findOrFail($storeId);


            $seller = $store->seller()->first();

            return view('edit_store', ['store' => $store, 'seller' => $seller]);
        } catch (ModelNotFoundException $e) {

            abort(404, 'The store or seller you are trying to edit does not exist.');
        }
    }

    public function showAddCategoryForm($sellerId, $storeId) {
        $store = Store::findOrFail($storeId);


        $seller = $store->seller()->first();
        return view('sellers.addCategory', ['store' => $store, 'seller' => $seller]);
    }

    public function showAddProductForm($sellerId, $storeId){
        $store = Store::findOrFail($storeId);


        $seller = $store->seller()->first();
        return view('sellers.addProduct', ['store' => $store, 'seller' => $seller, 'categories' => $store->categories]);
    }

    public function showEditProductForm($sellerId, $storeId, $productId){
        $store = Store::findOrFail($storeId);
        $seller = $store->seller()->first();
        $product = Product::findOrFail($productId);
        return view('sellers.editProduct', ['store' => $store, 'seller' => $seller, 'product' => $product, 'categories' => $store->categories]);
    }

    public function viewOrder($orderId){
        try {
            $order = Order::findOrFail($orderId);
            return view('sellers.viewOrder')->with(['products' => $order->products, 'order' => $order]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
    public function changeOrderStatus($orderId){
        try {
            $order = Order::findOrFail($orderId);
            $order->order_status = "delivered";
            $order->save();
            Mail::to($order->user->email)->send(new OrderDeliveredMail());

            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
