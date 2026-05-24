@extends('layouts.app')

@section('title', 'Poin Loyalitas')

@section('content')

{{-- Status Loyalty --}}
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h3 class="font-semibold text-gray-700 mb-4">Status Poin Kamu</h3>

    <div class="flex items-center justify-between mb-3">
        <div class="flex gap-2">
            @for($i = 1; $i <= 10; $i++)
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                    {{ $i <= $user->cup_count ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-400' }}">
                    {{ $i <= $user->cup_count ? '☕' : $i }}
                </div>
            @endfor
        </div>
    </div>

    <div class="w-full bg-gray-100 rounded-full h-3 mb-4">
        <div class="bg-amber-600 h-3 rounded-full transition-all duration-500"
             style="width: {{ $progress }}%"></div>
    </div>

    @if($user->cup_count >= 10)
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
            <p class="text-green-700 font-semibold">Selamat! Kamu berhak dapat 1 cup kopi gratis!</p>
            <p class="text-green-600 text-sm mt-1">Tunjukkan halaman ini ke kasir saat memesan.</p>
        </div>
    @else
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <p class="text-amber-800 font-medium">☕ {{ $user->cup_count }} dari 10 cup terkumpul</p>
            <p class="text-amber-700 text-sm mt-1">Butuh <strong>{{ $cupsNeeded }} cup lagi</strong> untuk mendapatkan 1 cup gratis!</p>
        </div>
    @endif
</div>

{{-- Riwayat Semua Transaksi --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h3 class="font-semibold text-gray-700">Riwayat Transaksi</h3>
    </div>

    @if($riwayat->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">🧾</p>
            <p>Belum ada riwayat transaksi.</p>
        </div>
    @else
        <div class="divide-y divide-gray-100">
            @foreach($riwayat as $order)
            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                <div>
                    <p class="text-sm font-medium text-gray-700">
                        {{ $order->orderDetails->map(fn($d) => $d->product->name . ' x' . $d->qty)->join(', ') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="text-right flex-shrink-0 ml-4">
                    <p class="text-sm font-bold text-amber-700">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                    @if($order->discount_amount > 0)
                        <p class="text-xs text-green-600 mt-0.5">Diskon loyalty ✓</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="px-6 py-4 border-t">
            {{ $riwayat->links() }}
        </div>
    @endif
</div>

@endsection
