<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    public function changeCurrency(Request $request){
        $request->validate([
            'currency' => 'required|in:USD,EUR,GBP,LBP,KWD',
        ]);
        $user = User::find(Auth::user()->id);
        $user->currency = $request->currency;
        $user->save();

        return back();
    }
}
