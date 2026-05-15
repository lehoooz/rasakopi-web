<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function index()
    {
        // Jika staff (admin/kasir) sudah login, langsung arahkan ke panel masing-masing
        if (Auth::check()) {
            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect('/admin');
            }

            if ($role === 'kasir') {
                return redirect('/kasir/pos');
            }
        }

        $products = Product::where('status', 'available')->get();
        return view('landing', compact('products'));
    }
}
