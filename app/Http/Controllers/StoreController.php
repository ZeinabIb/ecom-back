<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\StoreAwaitingReviewNotification;
use App\Mail\StoreReviewEmail;
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
        if(auth()->user()->user_type=="seller"){

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
                return response()->json(['message' =>  'There was an error adding your store.', 'exception_message' => $th->getMessage()], 402);
            }

            return response()->json(['message' => 'Store created successfully!'], 200);
        }else{
            return response()->json(['message' => 'Unauthorized'], 401);
        }

    }

    public function reviewStore(Request $request){
        if(auth()->user()->user_type=="admin"){
            $request->validate([
                'store_id'=>'required|int',
                'review_status'=>'required|string', // "approved" / "rejected"
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
        if(auth()->user()->user_type=="admin"){
            $stores = Store::All();
            return response()->json(['message' => 'Stores retreived successfully', 'data' => $stores], 200);
        }else{
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
    }

    public function getAllApprovedStores(){
        if(auth()->user()){
            $stores = Store::All()
            ->where('store_status', "approved");
            return response()->json(['message' => 'Approved stores retreived successfully', 'data' => $stores], 200);
        }else{
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
    }

    public function getAllStoresForSeller(){
        if(auth()->user()->user_type=="seller"){
            $stores = Store::All()
            ->where("seller_id", auth()->user()->id);

            return response()->json(['message' => 'Seller \''. auth()->user()->username .'\' stores retreived successfully', 'data' => $stores], 200);
        }else{
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
    }

    // STORE - END //

    // CATEGORY - START //

    public function addCategory(Request $request){
        if(auth()->user()->user_type=="seller"){
            $request->validate([
                'store_id'=>'required|int',
                'newCategory'=>'required|string'
            ]);
            
            // first we check id the store_id belongs to this user
            $store = Store::find($request->input('store_id'));
            if($store->seller_id!=auth()->user()->id){
                return response()->json(['message' => 'How about you add categories to your OWN store instead.'], 401);
            }
            

            try {
                $category = Category::create([
                'name' => $request->input('newCategory'),
                'store_id'=>$request->input('store_id'),
            ]);

            return response()->json(['message' => 'The category has succesfully been added.'], 200);

            } catch (\Throwable $th) {
                return response()->json(['message' =>  'There was an error adding your category.', 'exception_message' => $th->getMessage()], 402);
            }
        }else{
            return response()->json(['message' => 'Unauthorized.'], 401);
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

    public function deleteCategory(Request $request){
        if(auth()->user()->user_type=="seller"){
            try {
                $request->validate([
                    'category_id'=>'required|int'
                ]);
                // check if the seller owns the store that this category belongs to.
                $category = Category::find($request->category_id);
                $store = $category->store;
                if($store->seller_id==auth()->user()->id){
                    // if it is we delete the category
                    $category->delete();

                    return response()->json(['message' => 'Succesfully deleted category.'], 200);
                }else{
                    return response()->json(['message' => 'You cannot delete this category.'], 401);
                }
            } catch (\Throwable $th) {
                return response()->json(['message' =>  'There was an error while deleting this category.', 'exception_message' => $th->getMessage()], 403);
            }
        }else{
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
    }

    // CATEGORY - END //

    // PRODUCT - START //

    public function addProduct(Request $request){
        if(auth()->user()->user_type=="seller"){
            try {
                $validatedData = $request->validate([
                    'name' => 'required|string|max:255',
                    'product_description' => 'required|string',
                    'price' => 'required|numeric|between:0.00,999999.99',
                    'quantity' => 'required|int|min:0',
                    'image_url' => 'nullable|string|max:255',
                    'auction_status' => 'required|integer|between:0,1', // Assuming auction_status is a binary value (0 or 1)
                    'store_id' => 'required|exists:stores,id', // Assuming store_id references the id column in the stores table
                    'category_id' => 'required|exists:categories,id', // Assuming category_id references the id column in the categories table
                ]);
                // first we check id the store_id belongs to this user
                $store = Store::find($request->input('store_id'));
                if($store->seller_id!=auth()->user()->id){
                    return response()->json(['message' => 'How about you add products to your OWN store instead.'], 401);
                }

                // Create the product
                $product = Product::create($validatedData);

                return response()->json(['message' => 'Succesfully added product.'], 200);

            } catch (\Throwable $th) {
                return response()->json(['message' =>  'There was an error while adding this product.', 'exception_message' => $th->getMessage()], 403);
            }
        }else{
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
    }
    
    public function editProduct(Request $request, $productId){
        if(auth()->user()->user_type=="seller"){
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
                        return response()->json(['message' => 'How about you edit products from your OWN store instead.'], 401);
                    }
    
                    // Update the product with validated data
                    $product->update($validatedData);
    
                    return response()->json(['message' => 'Succesfully updated product.'], 200);
    
                } catch (\Throwable $th) {
                    return response()->json(['message' =>  'There was an error while editing this product.', 'exception_message' => $th->getMessage()], 403);
                }
        }else{
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
    }
    
    public function deleteProduct($productId){
        if(auth()->user()->user_type=="seller"){
            try {
                $product = Product::findOrFail($productId);
                // Check if the product belongs to the authenticated user's store
                $store = Store::find($product->store_id);
                if ($store->seller_id != auth()->user()->id) {
                    return response()->json(['message' => 'How about you delete products from your OWN store instead.'], 401);
                }

                $product->delete();

                return response()->json(['message' => 'Succesfully deleted product.'], 200);

            } catch (\Throwable $th) {
                return response()->json(['message' =>  'There was an error while deleting this product.', 'exception_message' => $th->getMessage()], 403);
            }
        }else{
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
    }
    
    public function getProducts($store_id){
        if(auth()->user()){
            try {
                $store = Store::find($store_id);

                return response()->json(['message' => 'Succesfully retreived products for store named '. $store->name .'.', 'data' => $store->products], 200);
            } catch (\Throwable $th) {
                return response()->json(['message' =>  'There was an error while retreiving products for this store.', 'exception_message' => $th->getMessage()], 403);
            }
        }else{
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
        
    }

    // PRODUCT - END //
}
