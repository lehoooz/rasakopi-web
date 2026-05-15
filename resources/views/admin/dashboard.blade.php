@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

{{-- Kartu Ringkasan --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">

    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500">
        <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-1">Total dari semua transaksi hari ini</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-red-400">
        <p class="text-sm text-gray-500">Pengeluaran Hari Ini</p>
        <p class="text-2xl font-bold text-red-500 mt-1">Rp {{ number_format($pengeluaranHariIni, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-1">Total pengeluaran operasional hari ini</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-amber-500">
        <p class="text-sm text-gray-500">Laba Bersih Hari Ini</p>
        <p class="text-2xl font-bold {{ $labaHariIni >= 0 ? 'text-amber-600' : 'text-red-600' }} mt-1">
            Rp {{ number_format($labaHariIni, 0, ',', '.') }}
        </p>
        <p class="text-xs text-gray-400 mt-1">Pendapatan dikurangi pengeluaran</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-400">
        <p class="text-sm text-gray-500">Total Member</p>
        <p class="text-2xl font-bold text-blue-500 mt-1">{{ $totalMember }}</p>
        <p class="text-xs text-gray-400 mt-1">Pelanggan terdaftar</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-purple-400">
        <p class="text-sm text-gray-500">Total Menu</p>
        <p class="text-2xl font-bold text-purple-500 mt-1">{{ $totalProduk }}</p>
        <p class="text-xs text-gray-400 mt-1">Produk di katalog</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-amber-800">
        <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
        <p class="text-2xl font-bold text-amber-800 mt-1">{{ $transaksiHariIni }}</p>
        <p class="text-xs text-gray-400 mt-1">Jumlah struk yang dibuat</p>
    </div>

</div>

{{-- Tabel Transaksi Terbaru --}}
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-base font-semibold text-gray-700 mb-4">Transaksi Terbaru</h3>

    @if($transaksiTerbaru->isEmpty())
        <div class="text-center py-10 text-gray-400">
            <p>Belum ada transaksi.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-gray-500 border-b">
                        <th class="pb-3 font-medium">#</th>
                        <th class="pb-3 font-medium">Kasir</th>
                        <th class="pb-3 font-medium">Member</th>
                        <th class="pb-3 font-medium">Total</th>
                        <th class="pb-3 font-medium">Diskon</th>
                        <th class="pb-3 font-medium">Grand Total</th>
                        <th class="pb-3 font-medium">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transaksiTerbaru as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 text-gray-400">#{{ $order->id }}</td>
                        <td class="py-3 font-medium text-gray-700">{{ $order->kasir->name ?? '-' }}</td>
                        <td class="py-3 text-gray-600">{{ $order->customer->name ?? '<span class="text-gray-400 italic">Umum</span>' }}</td>
                        <td class="py-3">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="py-3 text-red-500">
                            {{ $order->discount_amount > 0 ? '- Rp ' . number_format($order->discount_amount, 0, ',', '.') : '-' }}
                        </td>
                        <td class="py-3 font-semibold text-amber-700">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                        <td class="py-3 text-gray-400">{{ $order->created_at->format('H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
