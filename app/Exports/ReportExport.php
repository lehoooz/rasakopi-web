<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets
{
    public function __construct(
        protected string $startDate,
        protected string $endDate
    ) {}

    public function sheets(): array
    {
        return [
            'Transaksi'   => new TransaksiSheet($this->startDate, $this->endDate),
            'Pengeluaran' => new PengeluaranSheet($this->startDate, $this->endDate),
        ];
    }
}
