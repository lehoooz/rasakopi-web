<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function pos()
    {
        $products = Product::all();
        $members  = User::where('role', 'customer')->get();
        return view('kasir.pos', compact('products', 'members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'       => 'required|array|min:1',
            'items.*.id'  => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'customer_id' => 'nullable|exists:users,id',
        ]);

        DB::transaction(function () use ($request) {
            $customer    = $request->customer_id ? User::find($request->customer_id) : null;
            $totalPrice  = 0;
            $items       = [];

            foreach ($request->items as $item) {
                $product   = Product::findOrFail($item['id']);
                $subtotal  = $product->price * $item['qty'];
                $totalPrice += $subtotal;
                $items[]   = ['product' => $product, 'qty' => $item['qty'], 'subtotal' => $subtotal];
            }

            // Hitung diskon loyalty (setiap 10 cup = 1 cup gratis)
            $discount  = 0;
            $cupsBought = 0;
            foreach ($items as $item) {
                $cupsBought += $item['qty'];
            }

            if ($customer && ($customer->cup_count + $cupsBought) >= 10) {
                // Ambil harga produk termurah sebagai nilai diskon
                $cheapest = collect($items)->sortBy('product.price')->first();
                $discount = $cheapest['product']->price;
            }

            $grandTotal = $totalPrice - $discount;

            $order = Order::create([
                'user_id'         => Auth::id(),
                'customer_id'     => $customer?->id,
                'total_price'     => $totalPrice,
                'discount_amount' => $discount,
                'grand_total'     => $grandTotal,
            ]);

            foreach ($items as $item) {
                OrderDetail::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product']->id,
                    'qty'        => $item['qty'],
                    'subtotal'   => $item['subtotal'],
                ]);
            }

            // Update cup_count member
            if ($customer) {
                $newCount = $customer->cup_count + $cupsBought;
                if ($discount > 0) {
                    $newCount = $newCount - 10; // reset setelah klaim
                }
                $customer->update(['cup_count' => $newCount]);
            }
        });

        return redirect('/kasir/pos')->with('success', 'Transaksi berhasil disimpan!');
    }

    public function history()
    {
        // Semua transaksi hari ini (riwayat)
        $orders = Order::with(['customer', 'kasir', 'orderDetails.product'])
            ->whereDate('created_at', today())
            ->latest()
            ->paginate(15);

        return view('kasir.history', compact('orders'));
    }
}
