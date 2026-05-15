<?php

namespace App\Http\Controllers;

use App\Models\Product;

class LandingController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'available')->get();
        return view('landing', compact('products'));
    }
}
