<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QrOrderController extends Controller
{
    /**
     * Privat helper untuk memblokir Admin/Kasir agar tidak bisa akses menu QR pelanggan
     */
    private function blockStaff()
    {
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'kasir'])) {
            $dashboard = Auth::user()->role === 'admin' ? '/admin' : '/kasir';
            return redirect($dashboard)->with('error', 'Staff tidak diperbolehkan memesan melalui menu QR pelanggan.');
        }
        return null;
    }

    public function index(Request $request)
    {
        if ($redirect = $this->blockStaff()) return $redirect;

        // Validasi Meja: Jika ada parameter 'meja' di URL
        if ($request->has('meja')) {
            $tableExists = \App\Models\Table::where('number', $request->meja)->exists();
            
            if (!$tableExists) {
                // Tampilkan 404 Normal jika meja tidak terdaftar
                abort(404);
            }
            
            session(['table_number' => $request->meja]);
        }

        $tableNumber = session('table_number');
        
        // Tampilkan 404 jika tidak ada meja di session (akses ilegal tanpa scan QR)
        if (!$tableNumber) {
            abort(404);
        }

        $products = Product::all();
        $cart = session()->get('qr_cart', []);
        
        $totalItems = 0;
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalItems += $item['quantity'];
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return view('qr.menu', compact('products', 'cart', 'totalItems', 'totalPrice', 'tableNumber'));
    }

    public function addToCart(Request $request)
    {
        if ($this->blockStaff()) return response()->json(['error' => 'Unauthorized'], 403);

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('qr_cart', []);
        $id = $product->id;
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }
        
        session()->put('qr_cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'cart' => $cart,
                'totalItems' => array_sum(array_column($cart, 'quantity')),
                'totalPrice' => array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart)),
                'currentQty' => $cart[$id]['quantity']
            ]);
        }

        return redirect()->back()->with('success', 'Produk ditambahkan');
    }

    public function removeFromCart(Request $request)
    {
        if ($this->blockStaff()) return response()->json(['error' => 'Unauthorized'], 403);

        $cart = session()->get('qr_cart', []);
        $id = $request->product_id;
        $currentQty = 0;

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
                $currentQty = $cart[$id]['quantity'];
            } else {
                unset($cart[$id]);
                $currentQty = 0;
            }
            session()->put('qr_cart', $cart);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'cart' => $cart,
                'totalItems' => array_sum(array_column($cart, 'quantity')),
                'totalPrice' => array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart)),
                'currentQty' => $currentQty
            ]);
        }

        return redirect()->back()->with('success', 'Produk dikurangi');
    }

    public function checkout()
    {
        if ($redirect = $this->blockStaff()) return $redirect;

        $cart = session()->get('qr_cart', []);
        if (count($cart) === 0) return redirect('/qr-menu');
        
        $totalPrice = array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart));
        $tableNumber = session('table_number', 'Tanpa Meja');
        
        // Loyalitas Logic
        $discountAmount = 0;
        $customer = Auth::user();
        $totalCupsInCart = array_sum(array_column($cart, 'quantity'));

        if ($customer && $customer->role === 'customer') {
            if (($customer->cup_count + $totalCupsInCart) >= 10) {
                // Ambil harga produk termurah di keranjang sebagai diskon
                $prices = array_column($cart, 'price');
                $discountAmount = min($prices);
            }
        }
        
        return view('qr.checkout', compact('cart', 'totalPrice', 'tableNumber', 'discountAmount'));
    }

    public function processCheckout(Request $request)
    {
        if ($redirect = $this->blockStaff()) return $redirect;

        $request->validate(['customer_name' => 'nullable|string|max:255']);
        $cart = session()->get('qr_cart', []);
        if (count($cart) === 0) return redirect('/qr-menu');

        $customerName = $request->customer_name ?: 'Pelanggan Umum';

        $totalPrice = array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cart));
        $totalCupsInCart = array_sum(array_column($cart, 'quantity'));

        $order = DB::transaction(function () use ($request, $cart, $totalPrice, $totalCupsInCart, $customerName) {
            $customer = Auth::user();
            $discountAmount = 0;

            if ($customer && $customer->role === 'customer') {
                if (($customer->cup_count + $totalCupsInCart) >= 10) {
                    $prices = array_column($cart, 'price');
                    $discountAmount = min($prices);
                }
            }

            $grandTotal = $totalPrice - $discountAmount;

            $order = Order::create([
                'user_id' => null,
                'customer_id' => $customer?->id,
                'customer_name' => $customerName,
                'table_number' => session('table_number', 'Tanpa Meja'),
                'order_type' => 'qr',
                'payment_method' => 'qris',
                'status' => 'pending',
                'total_price' => $totalPrice,
                'discount_amount' => $discountAmount,
                'grand_total' => $grandTotal,
            ]);

            foreach ($cart as $id => $details) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'qty' => $details['quantity'],
                    'subtotal' => $details['price'] * $details['quantity']
                ]);
            }

            // Update cup_count member
            if ($customer && $customer->role === 'customer') {
                $newCount = $customer->cup_count + $totalCupsInCart;
                if ($discountAmount > 0) {
                    $newCount = $newCount - 10;
                }
                // Kita perlu ambil instance user yang fresh agar bisa diupdate jika sessionnya belum update
                User::find($customer->id)->update(['cup_count' => $newCount]);
            }

            return $order;
        });

        session()->forget('qr_cart');
        return redirect('/qr-menu/success')->with(['customer_name' => $customerName]);
    }

    public function success()
    {
        if ($redirect = $this->blockStaff()) return $redirect;

        $name = session('customer_name', 'Kakak');
        $tableNumber = session('table_number', '');
        return view('qr.success', compact('name', 'tableNumber'));
    }
}
