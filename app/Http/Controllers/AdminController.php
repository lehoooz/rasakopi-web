<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Expense;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        $pendapatanHariIni = Order::whereDate('created_at', $today)->sum('grand_total');
        $pengeluaranHariIni = Expense::whereDate('expense_date', $today)->sum('amount');
        $labaHariIni = $pendapatanHariIni - $pengeluaranHariIni;

        $totalMember = User::where('role', 'customer')->count();
        $totalProduk = Product::count();
        $transaksiHariIni = Order::whereDate('created_at', $today)->count();

        $transaksiTerbaru = Order::with(['kasir', 'customer'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'pendapatanHariIni',
            'pengeluaranHariIni',
            'labaHariIni',
            'totalMember',
            'totalProduk',
            'transaksiHariIni',
            'transaksiTerbaru'
        ));
    }
}
