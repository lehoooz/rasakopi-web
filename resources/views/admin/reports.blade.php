@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')

{{-- Filter Tanggal --}}
<div class="bg-white rounded-xl shadow-sm p-5 mb-6">
    <form method="GET" action="/admin/reports" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate }}"
                class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate }}"
                class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
        </div>
        <button type="submit" class="bg-amber-800 hover:bg-amber-900 text-white font-semibold px-5 py-2 rounded-lg text-sm transition">
            Tampilkan
        </button>
        <a href="/admin/reports/export?start_date={{ $startDate }}&end_date={{ $endDate }}"
            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2 rounded-lg text-sm transition flex items-center gap-2">
            ⬇ Export Excel
        </a>
    </form>
</div>

{{-- Kartu Ringkasan --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500">
        <p class="text-sm text-gray-500">Total Pendapatan</p>
        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-red-400">
        <p class="text-sm text-gray-500">Total Pengeluaran</p>
        <p class="text-2xl font-bold text-red-500 mt-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-amber-500">
        <p class="text-sm text-gray-500">Laba / Rugi</p>
        <p class="text-2xl font-bold {{ $labaRugi >= 0 ? 'text-amber-600' : 'text-red-600' }} mt-1">
            Rp {{ number_format($labaRugi, 0, ',', '.') }}
        </p>
    </div>
</div>

{{-- Tabel Transaksi --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b">
        <h3 class="font-semibold text-gray-700">Rincian Transaksi ({{ $orders->count() }} struk)</h3>
    </div>
    @if($orders->isEmpty())
        <div class="text-center py-10 text-gray-400">
            <p>Tidak ada transaksi pada periode ini.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 border-b">
                    <tr>
                        <th class="px-6 py-3 font-medium">#</th>
                        <th class="px-6 py-3 font-medium">Tanggal</th>
                        <th class="px-6 py-3 font-medium">Kasir</th>
                        <th class="px-6 py-3 font-medium">Member</th>
                        <th class="px-6 py-3 font-medium">Item</th>
                        <th class="px-6 py-3 font-medium">Diskon</th>
                        <th class="px-6 py-3 font-medium">Grand Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 align-top">
                        <td class="px-6 py-3 text-gray-400">#{{ $order->id }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $order->kasir->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-gray-600">{!! $order->customer ? e($order->customer->name) : '<span class="italic text-gray-400">Umum</span>' !!}</td>
                        <td class="px-6 py-3 text-gray-600">
                            @foreach($order->orderDetails as $d)
                                <span class="block">{{ $d->product->name }} x{{ $d->qty }}</span>
                            @endforeach
                        </td>
                        <td class="px-6 py-3 text-red-500">
                            {{ $order->discount_amount > 0 ? '- Rp ' . number_format($order->discount_amount, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-3 font-semibold text-amber-700">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Tabel Pengeluaran --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h3 class="font-semibold text-gray-700">Rincian Pengeluaran ({{ $expenses->count() }} item)</h3>
    </div>
    @if($expenses->isEmpty())
        <div class="text-center py-10 text-gray-400">
            <p>Tidak ada pengeluaran pada periode ini.</p>
        </div>
    @else
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 border-b">
                <tr>
                    <th class="px-6 py-3 font-medium">Tanggal</th>
                    <th class="px-6 py-3 font-medium">Keterangan</th>
                    <th class="px-6 py-3 font-medium">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($expenses as $expense)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-gray-500">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $expense->description }}</td>
                    <td class="px-6 py-3 font-medium text-red-500">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
