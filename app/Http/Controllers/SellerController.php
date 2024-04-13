<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{
    public function index()
    {

        $sellers = User::where('user_type', 'seller')->get();

        return view('sellers.index', ['sellers' => $sellers]);
    }

    public function show(User $seller)
    {
        return view('sellers.seller', ['seller' => $seller]);
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
    public function resetPassword(Request $request, User $seller)
    {
        // Validate the request
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:6',
        ]);

        // Check if the old password matches the one in the database
        if (Hash::check($request->old_password, $seller->password)) {
            // Update the password
            $seller->password = Hash::make($request->password);
            $seller->save();

            return redirect()->back()->with('success', 'Password updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Old password does not match.');
        }
    }



}
