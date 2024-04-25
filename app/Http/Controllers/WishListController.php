<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{
     public function removeProductFromWishlist($product_id)
    {
        try {
            $wishlist = Auth::user()->wishlist;

            $productInwishlist = $wishlist->products()->where('id', $product_id)->first();

            if ($productInwishlist) {
                $wishlist->products()->detach($product_id);

                return redirect()->back();
            } else {
                return redirect()->route('home.home');
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
        
    }

    public function addProductTowishlist(Request $request, $store_id, $product_id){
        try {
            // Get product
            $product = Product::findOrFail($product_id);
    
            // Retrieve the user's wishlist
            $wishlist = auth()->user()->wishlist;

            $wishlist->products()->attach($product->id);
    
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function getwishlist(){
        return view('home.wishlist');
    }
}
