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
     PANEL: PESANAN QR MASUK (Live Queue)
════════════════════════════════════════════════════════ --}}
<div style="margin-bottom:28px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <div>
            <h2 style="font-size:18px;font-weight:800;color:#1C1009;letter-spacing:-0.3px;">📲 Pesanan QR Masuk</h2>
            <p style="font-size:13px;color:#9C7B6A;margin-top:2px;">Antrean pesanan dari meja hari ini</p>
        </div>
        @if($qrOrders->count() > 0)
            <span style="background:#FEF3C7;color:#92400E;font-size:13px;font-weight:700;padding:6px 14px;border-radius:999px;">
                {{ $qrOrders->count() }} pesanan menunggu
            </span>
        @endif
    </div>

    @if($qrOrders->isEmpty())
        <div style="background:#fff;border-radius:18px;border:1.5px dashed #F0E0D0;padding:40px 20px;text-align:center;color:#9C7B6A;">
            <p style="font-size:36px;margin-bottom:8px;">🎉</p>
            <p style="font-weight:600;font-size:14px;">Tidak ada antrean pesanan QR saat ini.</p>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px;">
            @foreach($qrOrders as $qrOrder)
            <div style="background:#fff;border-radius:18px;box-shadow:0 4px 16px rgba(44,26,14,0.08);border:1px solid #F0E0D0;overflow:hidden;">
                {{-- Card Header --}}
                <div style="background:linear-gradient(135deg,#2C1A0E,#5C3317);padding:14px 18px;display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <p style="color:rgba(255,255,255,0.65);font-size:11px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;">Meja</p>
                        <p style="color:#fff;font-size:20px;font-weight:800;letter-spacing:-0.5px;">{{ $qrOrder->table_number ?? 'Tanpa Meja' }}</p>
                    </div>
                    <div style="text-align:right;">
                        <span style="background:#FBBF24;color:#2C1A0E;font-size:11px;font-weight:800;padding:4px 10px;border-radius:999px;text-transform:uppercase;letter-spacing:0.5px;">QRIS</span>
                        <p style="color:rgba(255,255,255,0.6);font-size:12px;margin-top:4px;">{{ $qrOrder->created_at->format('H:i') }}</p>
                    </div>
                </div>

                {{-- Card Body --}}
                <div style="padding:16px 18px;">
                    <p style="font-size:13px;font-weight:700;color:#2C1A0E;margin-bottom:10px;">
                        👤 {{ $qrOrder->customer_name ?? 'Tamu' }}
                    </p>

                    {{-- Item List --}}
                    <div style="background:#FFF8F0;border-radius:10px;padding:10px 12px;margin-bottom:12px;">
                        @foreach($qrOrder->orderDetails as $detail)
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:4px 0;border-bottom:1px solid #F0E0D0;">
                                <span style="font-size:13px;color:#3D2314;font-weight:500;">
                                    {{ $detail->qty }}x {{ $detail->product->name ?? 'Produk' }}
                                </span>
                                <span style="font-size:13px;font-weight:700;color:#5C3317;">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                        <div style="display:flex;justify-content:space-between;align-items:center;padding-top:8px;">
                            <span style="font-size:13px;font-weight:700;color:#2C1A0E;">Total</span>
                            <span style="font-size:15px;font-weight:800;color:#5C3317;">Rp {{ number_format($qrOrder->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Centang Done --}}
                    <form action="/kasir/orders/{{ $qrOrder->id }}/complete" method="POST">
                        @csrf
                        <button type="submit"
                            style="width:100%;padding:12px;background:linear-gradient(135deg,#2C1A0E,#5C3317);color:#fff;border:none;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:8px;"
                            onclick="return confirm('Tandai pesanan ini selesai?')">
                            ✓ Selesai & Diantarkan
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

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
                        <th class="px-5 py-3 font-medium">Tipe</th>
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
                            @if($order->payment_method === 'qr')
                                <span class="font-semibold text-gray-700">{{ $order->customer_name ?? 'Tamu' }}</span>
                                <span class="block text-xs text-gray-400">Meja {{ $order->table_number }}</span>
                            @else
                                {!! $order->customer ? '<span class="font-semibold text-gray-700">'.e($order->customer->name).'</span>' : '<span class="italic text-gray-400">Umum</span>' !!}
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @if($order->payment_method === 'qr')
                                <span style="background:#FEF3C7;color:#92400E;font-size:11px;font-weight:700;padding:3px 8px;border-radius:999px;">QR</span>
                            @else
                                <span style="background:#E0F2FE;color:#0369A1;font-size:11px;font-weight:700;padding:3px 8px;border-radius:999px;">Kasir</span>
                            @endif
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
