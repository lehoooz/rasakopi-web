<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class AdminTableController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('number')->get();
        return view('admin.tables.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|string|unique:tables,number',
        ]);

        Table::create([
            'number' => $request->number,
            'status' => 'available',
        ]);

        return redirect()->back()->with('success', 'Meja berhasil ditambahkan.');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->back()->with('success', 'Meja berhasil dihapus.');
    }

    public function print()
    {
        $tables = Table::orderBy('number')->get();
        return view('admin.tables.print', compact('tables'));
    }
}
