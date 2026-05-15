<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::whereDate('expense_date', today())->latest()->get();
        $totalHariIni = $expenses->sum('amount');
        return view('kasir.expenses', compact('expenses', 'totalHariIni'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description'  => 'required|string|max:200',
            'amount'       => 'required|integer|min:1',
            'expense_date' => 'required|date',
        ]);

        Expense::create($request->only('description', 'amount', 'expense_date'));

        return back()->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
