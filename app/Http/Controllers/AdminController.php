<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class AdminController extends Controller
{
    public function index()
    {
        $stores = Store::all(); // Retrieve all stores from the database
        return view('admin', ['stores' => $stores]); // Pass the stores data to the admin view
    }

    public function toggleStoreStatus(Request $request, Store $store)
    {
        // Toggle the store status
        $store->store_status = $store->store_status === 'Active' ? 'Inactive' : 'Active';
        $store->save();

        // Redirect back to the admin page
        return redirect()->route('admin.index');
    }
}
