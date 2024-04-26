<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Events\BidMade;
use App\Events\NewOrder;
use App\Mail\OrderCreatedMail;
use App\Mail\OrderReceivedMail;
use Stripe;
use App\Models\Store;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\StoreAwaitingReviewNotification;
use App\Mail\StoreReviewEmail;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Invitation;
use App\Models\Product;
use App\Models\User;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
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

    public function getStoresLike(Request $request){
        $searchQuery = $request->input('search_store');
        $stores = Store::where('name', 'like', "%$searchQuery%")
                            ->get();

        return view('home.stores')->with(['all_stores'=>$stores]);
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
                    'image_url' => 'nullable|image|max:700',
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
                    $auction = Auction::create([
                        'starting_price' => $request->price,
                        'current_highest_bid' =>$request->price,
                        'product_id' => $product->id,
                        'store_id' => $storeId,
                    ]);

                    $auction->auction_channel = 'auction_' . $product->id . '_' . $product->store_id;
                    $auction->save();
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

    public function getProductsLike(Request $request, $store_id){

        $searchQuery = $request->input('search_product');
        $store = Store::find($store_id);
        $products = Product::where('store_id', $store_id)
                            ->where('name', 'like', "%$searchQuery%")
                            ->get();

        return view('home.store_view')->with(['all_products'=>$products, 'all_categories' => $store->categories]);
    }

    public function productDetails($store_id, $product_id){
        try {
            return view('home.product_details')->with(['product'=>Product::findOrFail($product_id)]);
        } catch (\Throwable $th) {
            return redirect()->route('home.home');
        }
    }

    public function removeProductFromCart($product_id)
    {
        try {
            $cart = Auth::user()->cart;

            $productInCart = $cart->products()->where('id', $product_id)->first();

            if ($productInCart) {
                $cart->products()->detach($product_id);

                return redirect()->back();
            } else {
                return redirect()->route('home.home');
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
        
    }

    public function addProductToCart(Request $request, $store_id, $product_id){
        try {
            // Get product
            $product = Product::findOrFail($product_id);
    
            // Retrieve the user's cart
            $cart = auth()->user()->cart;
    
            // Get the quantity from the request
            $quantity = $request->num_product;
    
            // Check if the product already exists in the cart
            if ($cart->products->contains($product->id)) {
                // If the product exists, update the quantity
                $cart->products()->updateExistingPivot($product->id, ['quantity' => $quantity]);
            } else {
                // If the product does not exist, attach it with the quantity
                $cart->products()->attach($product->id, ['quantity' => $quantity]);
            }
    
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function getCart(){
        return view('home.cart');
    }

    public function stripe(Request $request) {
        return view('home.stripe')->with(['totalprice'=>$request->totalprice, 'location_lat'=>$request->location_lat, 'location_lng'=>$request->location_lng]);
    }

    public function stripePost(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    
        Stripe\Charge::create ([
                "amount" => $request->totalprice * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Thanks for payment." 
        ]);

        // Retrieve location data
        $locationLat = $request->location_lat;
        $locationLng = $request->location_lng;

        // Fetch products from the authenticated user's cart
        $user = Auth::user();
        $products = $user->cart->products;

        // Group products by store (seller)
        $groupedProducts = $products->groupBy('store_id');

        // Create orders and associate products with them
        foreach ($groupedProducts as $storeId => $storeProducts) {
            // Get the seller ID from the first product's store
            $sellerId = $storeProducts->first()->store->seller_id;

            $order = new Order();
            $order->buyer_id = $user->id;
            $order->seller_id = $sellerId; // Set the seller ID
            $order->location_lat = $locationLat;
            $order->location_lng = $locationLng;
            $order->total_amount = 0; // Initialize total price for this order
            $order->payment_status = "Paid Via Card";

            // Save the order
            $order->save();

            // Calculate total price for this order
            $totalPrice = 0;

            // Associate products with the order and calculate total price
            foreach ($storeProducts as $product) {
                $quantityInCart = $product->pivot->quantity;
                $order->products()->attach($product->id, ['quantity' => $quantityInCart]);
                $totalPrice += $product->price * $quantityInCart;
            }

            // Update total price for this order
            $order->total_amount = $totalPrice;
            $order->save();
            $seller_email = $storeProducts->first()->store->seller->email;
            Mail::to($seller_email)->send(new OrderReceivedMail($order->products));

            // Dispatch the NewOrder event
            event(new NewOrder());
        }

        Mail::to(auth()->user()->email)->send(new OrderCreatedMail());

        // Clear the user's cart
        $user->cart->products()->detach();

        return back()->with('success_message', 'Payment successful. Orders created.');
    }

    public function cashOnDelivery(Request $request){
        
        // Retrieve location data
        $locationLat = $request->location_lat;
        $locationLng = $request->location_lng;

        // Fetch products from the authenticated user's cart
        $user = Auth::user();
        $products = $user->cart->products;

        // Group products by store (seller)
        $groupedProducts = $products->groupBy('store_id');

        // Create orders and associate products with them
        foreach ($groupedProducts as $storeId => $storeProducts) {
            // Get the seller ID from the first product's store
            $sellerId = $storeProducts->first()->store->seller_id;

            $order = new Order();
            $order->buyer_id = $user->id;
            $order->seller_id = $sellerId; // Set the seller ID
            $order->location_lat = $locationLat;
            $order->location_lng = $locationLng;
            $order->total_amount = 0; // Initialize total price for this order
            $order->payment_status = "Cash On Delivery";

            // Save the order
            $order->save();

            // Calculate total price for this order
            $totalPrice = 0;

            // Associate products with the order and calculate total price
            foreach ($storeProducts as $product) {
                $quantityInCart = $product->pivot->quantity;
                $order->products()->attach($product->id, ['quantity' => $quantityInCart]);
                $totalPrice += $product->price * $quantityInCart;
            }

            // Update total price for this order
            $order->total_amount = $totalPrice;
            $order->save();
            $seller_email = $storeProducts->first()->store->seller->email;
            Mail::to($seller_email)->send(new OrderReceivedMail($order->products));

            // Dispatch the NewOrder event
            event(new NewOrder());
        }

        Mail::to(auth()->user()->email)->send(new OrderCreatedMail());

        // Clear the user's cart
        $user->cart->products()->detach();

        return back()->with('success_message', 'Orders created.');
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

    public function getAuctions($sellerId, $storeId, $auctionId){
        try {
            $auction = Auction::findOrFail($auctionId);
            if ($auction->store->seller_id != auth()->user()->id) {
                return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
            }

            $store = Store::findOrFail($storeId);
            $seller = $store->seller()->first();

            return view('sellers.auctionDetails')->with(['store' => $store, 'seller' => $seller, 'auction' => $auction]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
        }
    }

    public function updateAuctions(Request $request, $sellerId, $storeId, $auctionId){
        try {
            $validatedData = $request->validate([
                'auction_start_time' => 'required|date',
                'auction_end_time' => 'required|date',
            ]);
            $auction = Auction::findOrFail($auctionId);
            if ($auction->store->seller_id != auth()->user()->id) {
                return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
            }

            $auction->auction_start_time = $request->auction_start_time;
            $auction->auction_end_time = $request->auction_end_time;
            $auction->save();

            $store = Store::findOrFail($storeId);
            $seller = $store->seller()->first();
            // return view('sellers.auctionDetails')->with(['store' => $store, 'seller' => $seller, 'auction' => $auction]);
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->route('sellers.show', ['seller' => auth()->user()->id]);
        }
    }

    public function getInvites(){
        $invites = Auth::user()->auctionInvites;
        return view('home.invites')->with(['invites'=>$invites]);
    }

    public function acceptInvite($inviteId){
        try {
            $invite = Invitation::findOrFail($inviteId);

        if($invite->buyer_id==Auth::user()->id){
            $invite->status = "accepted";
            $invite->save();
            return redirect()->back();
        }else{
            return redirect()->route('home.home');
        }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function declineInvite($inviteId){
        try {
            $invite = Invitation::findOrFail($inviteId);

        if($invite->buyer_id==Auth::user()->id){
            $invite->status = "declined";
            $invite->save();
            return redirect()->back();
        }else{
            return redirect()->route('home.home');
        }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }  
    }

    public function auctionEvent($auctionId){
        $auction = Auction::findOrFail($auctionId)->get()->first();
        $store = Store::findOrFail($auction->store_id)->get()->first();
        if((Auth::user()->auctionInvites->first()?Auth::user()->auctionInvites->first()->auction_id:null) == $auctionId || Auth::user()->id == $store->seller_id){
            return view('home.auction')->with(['auction' => $auction, 'bids' => $auction->bids]);
        }else{
            return redirect('/');
        }
        
    }

    public function placeBid(Request $request, $auctionId)
    {
        try {
            $auction = Auction::findOrFail($auctionId);

            // Convert auction start time and end time to Carbon instances
            $auctionStartTime = Carbon::parse($auction->auction_start_time);
            $auctionEndTime = Carbon::parse($auction->auction_end_time);
            
            // Get the current time
            $now = Carbon::now();
            $now->addHours(3);
            // Compare the current time with the auction start and end times
            if ($now->lt($auctionStartTime) || $now->gt($auctionEndTime)) {
                // Auction is not within the active period
                
                event(new BidMade($auctionId, Auth::user()->id, -1));
                return back()->with('error', 'Bidding is only allowed during the active period of the auction.');
            }

            Bid::create([
                'bid_amount' => $request->bid_amount,
                'buyer_id' => Auth::user()->id,
                'auction_id' => $auctionId
            ]);

            if($request->bid_amount > $auction->current_highest_bid || $auction->current_highest_bid == null){
                $auction->current_highest_bid = $request->bid_amount;
                $auction->save();
            }

            event(new BidMade($auctionId, Auth::user()->id, $request->bid_amount));

            return back();
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }  
    }

    public function closeAuction($auctionId){
        try {
            $auction = Auction::findOrFail($auctionId);

            // Convert auction start time and end time to Carbon instances
            $auctionStartTime = Carbon::parse($auction->auction_start_time);
            $auctionEndTime = Carbon::parse($auction->auction_end_time);

            // Check if the current time is within the auction start and end dates
            $now = Carbon::now();
            $now->addHours(3);
            
            
            if($auction->store->seller->id!=Auth::user()->id){
                return back();
            }

            if ($now->gt($auctionStartTime) && $now->lt($auctionEndTime)) {
                $auction->auction_end_time = $now;
                $auction->save();

                $order = new Order();
                $order->buyer_id = $auction->highestBid->user->id;
                $order->seller_id = $auction->store->seller_id; // Set the seller ID
                $order->location_lat = "0";
                $order->location_lng = "0";
                $order->total_amount = $auction->highestBid->bid_amount; // Initialize total price for this order
                $order->payment_status = "AUCTION: Cash On Delivery";

                // Save the order
                $order->save();

                $quantityInCart = 1;
                $order->products()->attach($auction->product_id, ['quantity' => $quantityInCart]);

                // Save the order
                $order->save();

                return redirect()->back();
            }
            return back();       
        } catch (\Throwable $th) {
            dd($th->getMessage());
        } 
    }
    
     public function getUserAuction(){
            $invites = Auth::user()->auctionInvites()->where('status', 'accepted')->get();
            $acceptedInvitesArray = $invites->toArray();
            $auctions = [];
            if($acceptedInvitesArray){
                $invitess = $invites;
            }

            return view('home.get_user_auctions', compact('invitess'));
    }

    // AUCTION - END //
}
