<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class PengeluaranSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
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
        return ['#', 'Tanggal', 'Keterangan', 'Jumlah'];
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
}
