<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\StoreAwaitingReviewNotification;
use App\Mail\StoreReviewEmail;
use App\Models\Auction;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreController extends Controller
{
    // STORE - START //

    public function addStore(Request $request){

        // checking if user is of type "seller"
        if(auth()->user()->usertype=="seller"){

            $request->validate([
                'name'=>'required|string',
                'description'=>'required|string',
                'motif'=>'required|string',
            ]);

            // after we validated the correct input data we add this beautiful store to the stores table with a status of pending

            try {
                $store = Store::create([
                'name' => $request->input('name'),
                'details'=>$request->input('description'),
                'motif'=>$request->input('motif'),
                'seller_id'=>auth()->user()->id,
            ]);

            // Send email notification to seller
            Mail::to(auth()->user()->email)->send(new StoreAwaitingReviewNotification);

            } catch (\Throwable $th) {
                // return response()->json(['message' =>  'There was an error adding your store.', 'exception_message' => $th->getMessage()], 402);
                redirect()->back();
            }

            // return response()->json(['message' => 'Store created successfully!'], 200);
            return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
        }else{
            // return response()->json(['message' => 'Unauthorized'], 401);
            redirect('/login');
        }

    }

    public function reviewStore(Request $request){
        if(auth()->user()->usertype=="admin"){
            $request->validate([
                'store_id'=>'required|int',
                'review_status'=>'required|string', // "Active" / "Inactive"
            ]);

            $updated = DB::table('stores')
            ->where('id', $request->store_id)
            ->update(['store_status' => $request->review_status]);



            if ($updated) {
                // we send an email to the seller to let him know
                $store = Store::find($request->store_id);
                $seller_email = User::find($store->seller_id);
                // Send email notification to seller
                Mail::to($seller_email)->send(new StoreReviewEmail($request->review_status, $store->name));

                return response()->json(['message' => 'The store has succesfully been '.$request->review_status], 200);
            } else {
                return response()->json(['message' => 'The store review was not successful.'], 404);
            }
        }else{
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
    }

    public function getAllStores(){
        if(auth()->user()->usertype=="admin"){
            $stores = Store::All();
            return response()->json(['message' => 'Stores retreived successfully', 'data' => $stores], 200);
        }else{
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
    }

    public function getAllApprovedStores(){
        // if(auth()->user()){
            $stores = Store::where('store_status', "Active")->get();
            // return response()->json(['message' => 'Approved stores retreived successfully', 'data' => $stores], 200);
            return view('home.stores')->with(['all_stores'=>$stores]);
        // }else{
        //     return response()->json(['message' => 'Unauthorized.'], 401);
        // }
    }

    public function getStore($store_id){
        if(auth()->user()){
            $store = Store::find($store_id);
            return response()->json(['message' => 'Store retreived successfully', 'data' => $store], 200);
        }else{
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
    }

    public function getAllStoresForSeller(){
        if(auth()->user()->usertype=="seller"){
            $stores = Store::All()
            ->where("seller_id", auth()->user()->id);

            return response()->json(['message' => 'Seller \''. auth()->user()->username .'\' stores retreived successfully', 'data' => $stores], 200);
        }else{
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
    }

    // STORE - END //

    // CATEGORY - START //

    public function addCategory(Request $request, $sellerId, $storeId){
            $request->validate([
                'name'=>'required|string'
            ]);
            
            // first we check id the store_id belongs to this user
            $store = Store::find($storeId);
            if($store->seller_id!=auth()->user()->id){
                return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
            }
            

            try {
                $category = Category::create([
                'name' => $request->input('name'),
                'store_id'=>$storeId,
            ]);

            return redirect()->route('sellers.editStore', ['seller' => auth()->user()->id, 'store' => $store->id]);

            } catch (\Throwable $th) {
                return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
            }
    }

    public function getCategoriesForStore($store_id){
        try {
            $store = Store::find($store_id); // Replace $storeId with the ID of the store you want to retrieve categories for
            $categories = $store->categories;

            return response()->json(['message' =>  'Categories for store '. $store->name . ' have been retreived.', 'data' => $categories], 200);

        } catch (\Throwable $th) {
            return response()->json(['message' =>  'There was an error while retrieving the categoried.', 'exception_message' => $th->getMessage()], 402);
        }   
    }

    public function deleteCategory(Request $request, $sellerId, $storeId, $categoryId){
            try {
                // check if the seller owns the store that this category belongs to.
                $category = Category::find($categoryId);
                $store = $category->store;
                if($store->seller_id==auth()->user()->id){
                    // if it is we delete the category
                    $category->delete();

                    return redirect()->route('sellers.editStore', ['seller' => auth()->user()->id, 'store' => $store->id]);
                }else{
                    return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
                }
            } catch (\Throwable $th) {
                return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
            }
    }

    // CATEGORY - END //

    // PRODUCT - START //

    public function addProduct(Request $request, $sellerId, $storeId){
            try {
                $validatedData = $request->validate([
                    'name' => 'required|string|max:255',
                    'product_description' => 'required|string',
                    'price' => 'required|numeric|between:0.00,999999.99',
                    'quantity' => 'int|min:0',
                    'image_url' => 'nullable|image|max:255',
                    'auction_status' => 'required|between:0,1', // Assuming auction_status is a binary value (0 or 1)
                    //'store_id' => 'required|exists:stores,id', // Assuming store_id references the id column in the stores table
                    'category_id' => 'required|exists:categories,id', // Assuming category_id references the id column in the categories table
                ]);
                
                // first we check id the store_id belongs to this user
                $store = Store::find($storeId);
                if($store->seller_id!=auth()->user()->id){
                    return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
                }

                $newImageName = time() .$store->id . '.' . $request->image_url->extension();
                $request->image_url->move('products', $newImageName);

                // Create the product
                $product = Product::create([
                    'name' => $request->input('name'),
                    'product_description'=>$request->input('product_description'),
                    'price'=>$request->input('price'),
                    'quantity'=>$request->input('quantity')?$request->input('quantity'):1,
                    'image_url'=>$newImageName,
                    'auction_status'=>$request->input('auction_status'),
                    'store_id'=>$storeId,
                    'category_id'=>$request->input('category_id'),                  
                ]);

                //if there's auction option let's make it
                if($request->auction_status==1){
                    Auction::create([
                        'starting_price' => $request->price,
                        'product_id' => $product->id,
                        'store_id' => $storeId
                    ]);
                }

                return redirect()->route('sellers.editStore', ['seller' => auth()->user()->id, 'store' => $store->id]);

            } catch (\Throwable $th) {
                dd($th->getMessage());
                return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
            }
    }
    
    public function editProduct(Request $request, $sellerId, $storeId, $productId){
                try {
                    $product = Product::findOrFail($productId);

                    $validatedData = $request->validate([
                        'name' => 'required|string|max:255',
                        'product_description' => 'required|string',
                        'price' => 'required|numeric|between:0.00,999999.99',
                        'quantity' => 'required|int|min:0',
                        'category_id' => 'required|exists:categories,id', // Assuming category_id references the id column in the categories table
                    ]);

                    // Check if the product belongs to the authenticated user's store
                    $store = Store::find($product->store_id);
                    if ($store->seller_id != auth()->user()->id) {
                        return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
                    }
    
                    // Update the product with validated data
                    $product->update($validatedData);
    
                    return redirect()->route('sellers.editStore', ['seller' => auth()->user()->id, 'store' => $store->id]);

                } catch (\Throwable $th) {
                    dd($th->getMessage());
                    return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
                }
    }
    
    public function deleteProduct($sellerId, $storeId, $productId){
            try {
                $product = Product::findOrFail($productId);
                // Check if the product belongs to the authenticated user's store
                $store = Store::find($product->store_id);
                if ($store->seller_id != auth()->user()->id) {
                    return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
                }

                $product->delete();

                return redirect()->route('sellers.editStore', ['seller' => auth()->user()->id, 'store' => $store->id]);

            } catch (\Throwable $th) {
                dd($th->getMessage());
                return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
            }
    }
    
    public function getProducts($store_id){
            try {
                $store = Store::find($store_id);
                return view('home.store_view')->with(['all_products'=>$store->products, 'all_categories' => $store->categories]);

                // return response()->json(['message' => 'Succesfully retreived products for store named '. $store->name .'.', 'data' => $store->products], 200);
            } catch (\Throwable $th) {
                return response()->json(['message' =>  'There was an error while retreiving products for this store.', 'exception_message' => $th->getMessage()], 403);
            }    
    }

    // PRODUCT - END //

    // AUCTION - START //

    public function deleteAuction($sellerId, $storeId, $auctionId){
        try {
            $auction = Auction::findOrFail($auctionId);
            if ($auction->store->seller_id != auth()->user()->id) {
                return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
            }
            $auction->product->delete();
            $auction->delete();
            return redirect()->route('sellers.editStore', ['seller' => auth()->user()->id, 'store' => $auction->store->id]);
        } catch (\Throwable $th) {
            return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
        }
    }

    // AUCTION - END //
}
