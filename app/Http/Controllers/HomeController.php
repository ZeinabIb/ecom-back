<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Models\Order;
use App\Models\OrderHasProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function HomeInfo()
    {
    $stores = Store::take(3)->get();
    $products = Product::take(3)->get();

        return view('home.home')->with(['all_stores' => $stores])->with(['all_products' => $products]);
    }
}
