<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user        = Auth::user();
        $cupsNeeded  = max(0, 10 - $user->cup_count);
        $progress    = min(100, ($user->cup_count / 10) * 100);

        $riwayat = Order::with('orderDetails.product')
            ->where('customer_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact('user', 'cupsNeeded', 'progress', 'riwayat'));
    }

    public function menu()
    {
        $products = Product::all();
        return view('customer.menu', compact('products'));
    }

    public function loyalty()
    {
        $user       = Auth::user();
        $cupsNeeded = max(0, 10 - $user->cup_count);
        $progress   = min(100, ($user->cup_count / 10) * 100);

        $riwayat = Order::with('orderDetails.product')
            ->where('customer_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('customer.loyalty', compact('user', 'cupsNeeded', 'progress', 'riwayat'));
    }
}
