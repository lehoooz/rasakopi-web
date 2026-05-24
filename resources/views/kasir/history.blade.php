@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')

{{-- FLASH MESSAGES --}}
@if(session('success'))
    <div style="background:#D1FAE5;color:#065F46;padding:12px 16px;border-radius:12px;margin-bottom:16px;font-weight:600;font-size:14px;">
        ✓ {{ session('success') }}
    </div>
@endif

{{-- ═══════════════════════════════════════════════════════
     TABEL: RIWAYAT TRANSAKSI HARI INI
════════════════════════════════════════════════════════ --}}
<div>
    <div style="margin-bottom:14px;">
        <h2 style="font-size:18px;font-weight:800;color:#1C1009;letter-spacing:-0.3px;">🧾 Riwayat Transaksi Hari Ini</h2>
        <p style="font-size:13px;color:#9C7B6A;margin-top:2px;">Semua transaksi yang telah selesai</p>
    </div>

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
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-5 py-3 font-medium">Pemesan</th>
                        <th class="px-5 py-3 font-medium">Item</th>
                        <th class="px-5 py-3 font-medium">Grand Total</th>
                        <th class="px-5 py-3 font-medium">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 align-top">
                        <td class="px-5 py-4 text-gray-400">#{{ $order->id }}</td>
                        <td class="px-5 py-4">
                            {!! $order->customer ? '<span class="font-semibold text-gray-700">'.e($order->customer->name).'</span>' : '<span class="italic text-gray-400">Umum</span>' !!}
                        </td>
                        <td class="px-5 py-4">
                            @foreach($order->orderDetails as $detail)
                                <p class="text-gray-700">{{ $detail->product->name }}
                                    <span class="text-gray-400">x{{ $detail->qty }}</span>
                                </p>
                            @endforeach
                        </td>
                        <td class="px-5 py-4 font-bold text-amber-700">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-gray-400">{{ $order->created_at->format('H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-5 py-4 border-t">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
