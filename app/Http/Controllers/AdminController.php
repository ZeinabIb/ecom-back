<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $stores = Store::all();
        $users = User::all();
        return view('admin', ['stores' => $stores, 'users' => $users]);
    }

    public function toggleStoreStatus(Request $request, Store $store)
    {
        $store->store_status = $store->store_status === 'Active' ? 'Inactive' : 'Active';
        $store->save();

        return redirect()->route('admin.index');
    }

    public function editStore(Store $store)
    {
        return view('edit_store', ['store' => $store]);
    }

    public function updateStore(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string',
            'details' => 'required|string',
            'store_status' => 'required|in:Active,Inactive',
        ]);

        $store->update([
            'name' => $request->name,
            'details' => $request->details,
            'store_status' => $request->store_status,
        ]);

        return redirect()->route('admin.index')->with('success', 'Store updated successfully');
    }
}
