@extends('layouts.app')

@section('title', 'Catat Pengeluaran')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Form Tambah Pengeluaran --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Tambah Pengeluaran</h3>

            <form method="POST" action="/kasir/expenses">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <input type="text" name="description" value="{{ old('description') }}"
                        placeholder="cth: Beli es batu, gula, dll"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 @error('description') border-red-400 @enderror">
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp)</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" min="1"
                        placeholder="0"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 @error('amount') border-red-400 @enderror">
                    @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="expense_date" value="{{ old('expense_date', today()->toDateString()) }}"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 @error('expense_date') border-red-400 @enderror">
                    @error('expense_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full bg-amber-800 hover:bg-amber-900 text-white font-semibold py-2 rounded-lg text-sm transition">
                    Simpan Pengeluaran
                </button>
            </form>
        </div>
    </div>

    {{-- Daftar Pengeluaran Hari Ini --}}
    <div class="lg:col-span-2">

        {{-- Kartu Total --}}
        <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-red-400 mb-4">
            <p class="text-sm text-gray-500">Total Pengeluaran Hari Ini</p>
            <p class="text-2xl font-bold text-red-500 mt-1">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($expenses->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <p class="text-4xl mb-3">💸</p>
                    <p>Belum ada pengeluaran hari ini.</p>
                </div>
            @else
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 border-b">
                        <tr>
                            <th class="px-6 py-3 font-medium">Keterangan</th>
                            <th class="px-6 py-3 font-medium">Jumlah</th>
                            <th class="px-6 py-3 font-medium">Tanggal</th>
                            <th class="px-6 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($expenses as $expense)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-700">{{ $expense->description }}</td>
                            <td class="px-6 py-4 font-medium text-red-500">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="/kasir/expenses/{{ $expense->id }}" class="inline" onsubmit="return confirm('Hapus pengeluaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-sm font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>

@endsection
