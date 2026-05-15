@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- Sapaan --}}
<div class="bg-amber-800 text-white rounded-xl p-6 mb-6">
    <p class="text-amber-200 text-sm">Selamat datang kembali,</p>
    <h2 class="text-2xl font-bold mt-1">{{ $user->name }} ☕</h2>
    <p class="text-amber-200 text-sm mt-1">Terima kasih sudah setia bersama Rasakopi!</p>
</div>

{{-- Progress Loyalty --}}
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold text-gray-700">Program Loyalitas — Buy 10 Get 1 Free</h3>
        <span class="text-amber-700 font-bold text-lg">{{ $user->cup_count }}/10 ☕</span>
    </div>

    <div class="w-full bg-gray-100 rounded-full h-4 mb-3">
        <div class="bg-amber-600 h-4 rounded-full transition-all duration-500"
             style="width: {{ $progress }}%"></div>
    </div>

    @if($user->cup_count >= 10)
        <p class="text-green-600 font-semibold text-sm">🎉 Selamat! Kamu berhak mendapatkan 1 cup kopi gratis pada transaksi berikutnya!</p>
    @else
        <p class="text-gray-500 text-sm">Butuh <span class="font-bold text-amber-700">{{ $cupsNeeded }} cup lagi</span> untuk mendapatkan 1 cup gratis!</p>
    @endif
</div>

{{-- Riwayat Transaksi Terakhir --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-700">Transaksi Terakhir</h3>
        <a href="/customer/loyalty" class="text-amber-700 text-sm hover:underline">Lihat semua →</a>
    </div>

    @if($riwayat->isEmpty())
        <div class="text-center py-10 text-gray-400">
            <p class="text-3xl mb-2">🧾</p>
            <p>Belum ada riwayat transaksi.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($riwayat as $order)
            <div class="flex items-center justify-between border rounded-lg px-4 py-3 hover:bg-gray-50">
                <div>
                    <p class="text-sm font-medium text-gray-700">
                        {{ $order->orderDetails->map(fn($d) => $d->product->name . ' x' . $d->qty)->join(', ') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-amber-700">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                    @if($order->discount_amount > 0)
                        <p class="text-xs text-green-600">Diskon loyalty ✓</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
