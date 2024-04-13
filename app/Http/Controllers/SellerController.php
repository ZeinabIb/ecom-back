<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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


}
