<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TransaksiSheet implements FromCollection, WithHeadings, WithTitle, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(
        protected string $startDate,
        protected string $endDate
    ) {}

    public function title(): string
    {
        return 'Transaksi & Laba Rugi';
    }

    public function headings(): array
    {
        return ['ID Transaksi', 'Tanggal Waktu', 'Nama Kasir', 'Nama Member', 'Item Pesanan', 'Total Harga', 'Diskon', 'Grand Total'];
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
        $items = $order->orderDetails->map(fn($d) => $d->product->name . ' (x' . $d->qty . ')')->join(', ');

        return [
            $order->id,
            $order->created_at->format('d/m/Y H:i'),
            $order->kasir->name ?? '-',
            $order->customer->name ?? 'Pelanggan Umum',
            $items,
            $order->total_price,
            $order->discount_amount,
            $order->grand_total,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = 'H'; // Kolom data transaksi sampai H
        $range = 'A1:' . $lastColumn . $lastRow;

        // Styling untuk baris Header (A1 - H1)
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1F4E78'], // Biru tua profesional
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Styling untuk tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(25);

        if ($lastRow > 1) {
            // Styling border untuk seluruh tabel data
            $sheet->getStyle($range)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // Styling baris selang-seling (Zebra striping) untuk data
            for ($row = 2; $row <= $lastRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFF2F2F2'], // Abu-abu sangat muda
                        ],
                    ]);
                }
            }

            // Format angka untuk kolom Harga (Total, Diskon, Grand Total) -> Kolom F, G, H
            $sheet->getStyle('F2:H' . $lastRow)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)');
            
            // Alignment rata tengah untuk ID dan Tanggal
            $sheet->getStyle('A2:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // ==============================================================
        // MENAMBAHKAN TABEL RINGKASAN LABA RUGI DI SAMPING KANAN (KOLOM J & K)
        // ==============================================================
        
        $totalPendapatan = Order::whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])->sum('grand_total');
        $totalPengeluaran = Expense::whereBetween('expense_date', [$this->startDate, $this->endDate])->sum('amount');
        $labaRugi = $totalPendapatan - $totalPengeluaran;

        $sheet->setCellValue('J2', 'RINGKASAN KEUANGAN');
        $sheet->mergeCells('J2:K2');

        $sheet->setCellValue('J3', 'Periode');
        $sheet->setCellValue('K3', \Carbon\Carbon::parse($this->startDate)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($this->endDate)->format('d/m/Y'));

        $sheet->setCellValue('J4', 'Total Pendapatan');
        $sheet->setCellValue('K4', $totalPendapatan);

        $sheet->setCellValue('J5', 'Total Pengeluaran');
        $sheet->setCellValue('K5', $totalPengeluaran);

        $sheet->setCellValue('J6', 'Laba / Rugi Bersih');
        $sheet->setCellValue('K6', $labaRugi);

        // Styling Tabel Ringkasan
        $sheet->getStyle('J2:K2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2E75B6']], // Biru Lighter
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(25);

        $sheet->getStyle('J3:J6')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF2F2F2']],
        ]);

        $sheet->getStyle('J2:K6')->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']],
            ]
        ]);

        // Format Uang untuk Ringkasan
        $sheet->getStyle('K4:K6')->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)');

        // Warna Hijau jika Laba, Merah jika Rugi
        if ($labaRugi >= 0) {
            $sheet->getStyle('K6')->getFont()->getColor()->setARGB('FF008000'); // Hijau
        } else {
            $sheet->getStyle('K6')->getFont()->getColor()->setARGB('FFFF0000'); // Merah
        }
        $sheet->getStyle('K6')->getFont()->setBold(true);

        // Atur lebar kolom J & K agar rapi
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
    }
}
