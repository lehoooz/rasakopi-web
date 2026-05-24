<?php

namespace App\Exports;

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

class PengeluaranSheet implements FromCollection, WithHeadings, WithTitle, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(
        protected string $startDate,
        protected string $endDate
    ) {}

    public function title(): string
    {
        return 'Pengeluaran';
    }

    public function headings(): array
    {
        return ['ID Pengeluaran', 'Tanggal', 'Keterangan Pengeluaran', 'Jumlah (Rp)'];
    }

    public function collection()
    {
        return Expense::whereBetween('expense_date', [$this->startDate, $this->endDate])
            ->latest('expense_date')
            ->get();
    }

    public function map($expense): array
    {
        return [
            $expense->id,
            \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y'),
            $expense->description,
            $expense->amount,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();
        $range = 'A1:' . $lastColumn . $lastRow;

        // Styling untuk baris Header
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFC00000'], // Merah tua profesional untuk pengeluaran
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Tinggi header
        $sheet->getRowDimension(1)->setRowHeight(25);

        if ($lastRow > 1) {
            // Styling border
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

            // Baris selang-seling (Zebra striping)
            for ($row = 2; $row <= $lastRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFFDF0F0'], // Merah sangat muda
                        ],
                    ]);
                }
            }

            // Format angka untuk kolom Jumlah (D)
            $sheet->getStyle('D2:D' . $lastRow)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)');
            
            // Alignment rata tengah
            $sheet->getStyle('A2:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
    }
}
