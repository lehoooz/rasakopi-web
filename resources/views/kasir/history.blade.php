@extends('layouts.app')

@section('title', 'Riwayat Transaksi Hari Ini')

@section('content')

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    @if($orders->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">🧾</p>
            <p>Belum ada transaksi hari ini.</p>
        </div>
    @else
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 border-b">
                <tr>
                    <th class="px-6 py-3 font-medium">#</th>
                    <th class="px-6 py-3 font-medium">Member</th>
                    <th class="px-6 py-3 font-medium">Item</th>
                    <th class="px-6 py-3 font-medium">Total</th>
                    <th class="px-6 py-3 font-medium">Diskon</th>
                    <th class="px-6 py-3 font-medium">Grand Total</th>
                    <th class="px-6 py-3 font-medium">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50 align-top">
                    <td class="px-6 py-4 text-gray-400">#{{ $order->id }}</td>
                    <td class="px-6 py-4 text-gray-600">{!! $order->customer ? e($order->customer->name) : '<span class="italic text-gray-400">Umum</span>' !!}</td>
                    <td class="px-6 py-4">
                        @foreach($order->orderDetails as $detail)
                            <p class="text-gray-700">{{ $detail->product->name }} <span class="text-gray-400">x{{ $detail->qty }}</span></p>
                        @endforeach
                    </td>
                    <td class="px-6 py-4">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-red-500">
                        {{ $order->discount_amount > 0 ? '- Rp ' . number_format($order->discount_amount, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-6 py-4 font-semibold text-amber-700">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-400">{{ $order->created_at->format('H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">
            {{ $orders->links() }}
        </div>
    @endif
</div>

@endsection
