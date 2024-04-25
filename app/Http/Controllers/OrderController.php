<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class OrderController extends Controller
{
     public function getBuyerOrders(){
        $orders = Order::where('buyer_id', Auth::user()->id)->get();

        return view ('home.orders', compact('orders'));
     }
}
