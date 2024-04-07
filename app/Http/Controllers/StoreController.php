<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\StoreAwaitingReviewNotification;
use App\Mail\StoreReviewEmail;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    //

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
                return response()->json(['message' =>  'There was an error adding your store.', 402]);
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
                return response()->json(['message' =>  'There was an error adding your category.', 402]);
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
            return response()->json(['message' =>  'There was an error while retrieving the categoried.', 402]);
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
                return response()->json(['message' =>  'There was an error while deleting this category.'], 403);
            }
        }else{
            return response()->json(['message' => 'Unauthorized.'], 401);
        }
    }
}
