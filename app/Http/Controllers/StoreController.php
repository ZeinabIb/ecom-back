<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\StoreAwaitingReviewNotification;

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
            return response()->json(['message' => 'May you find Jesus'], 401);
        }

    }
}
