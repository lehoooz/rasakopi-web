<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function __construct(
        protected string $startDate,
        protected string $endDate
    ) {}

    public function title(): string
    {
        return 'Transaksi';
    }

    public function headings(): array
    {
        return ['#', 'Tanggal', 'Kasir', 'Member', 'Item', 'Total', 'Diskon', 'Grand Total'];
    }

    public function collection()
    {
        return Order::with(['kasir', 'customer', 'orderDetails.product'])
            ->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->latest()
            ->get();
    }

    public function map($order): array
    {
        $items = $order->orderDetails->map(fn($d) => $d->product->name . ' x' . $d->qty)->join(', ');

        return [
            $order->id,
            $order->created_at->format('d/m/Y H:i'),
            $order->kasir->name ?? '-',
            $order->customer->name ?? 'Umum',
            $items,
            $order->total_price,
            $order->discount_amount,
            $order->grand_total,
        ];
    }
}
