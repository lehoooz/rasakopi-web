<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Expense;
use App\Exports\ReportExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', today()->toDateString());
        $endDate   = $request->get('end_date', today()->toDateString());

        $orders = Order::with(['kasir', 'customer', 'orderDetails.product'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest()
            ->get();

        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->latest('expense_date')
            ->get();

        $totalPendapatan  = $orders->sum('grand_total');
        $totalPengeluaran = $expenses->sum('amount');
        $labaRugi         = $totalPendapatan - $totalPengeluaran;

        return view('admin.reports', compact(
            'orders', 'expenses',
            'totalPendapatan', 'totalPengeluaran', 'labaRugi',
            'startDate', 'endDate'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->get('start_date', today()->toDateString());
        $endDate   = $request->get('end_date', today()->toDateString());

        $filename = 'laporan-rasakopi-' . $startDate . '-sd-' . $endDate . '.xlsx';

        return Excel::download(new ReportExport($startDate, $endDate), $filename);
    }
}
