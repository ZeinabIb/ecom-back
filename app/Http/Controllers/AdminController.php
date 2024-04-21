<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{ public function index()
    {
        $stores = Store::all();
        $users = User::all();

        return view('admin', ['stores' => $stores, 'users' => $users]);
    }

    public function getAllUsers()
{
    $users = User::all();
    return view('admin', ['users' => $users]);
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
public function resetUserPassword(Request $request, User $user)
{
    $request->validate([
        'old_password' => 'required|string',
        'new_password' => 'required|string|min:8',
    ]);

    if (!Hash::check($request->old_password, $user->password)) {
        return redirect()->back()->with('error', 'Old password is incorrect.');
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return redirect()->back()->with('success', 'Password reset successfully.');
}


}
