@extends('layouts.app')

@section('title', 'Manajemen Meja QR')

@section('content')

<div class="space-y-8">
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- FORM TAMBAH MEJA --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-amber-100 overflow-hidden">
                <div class="bg-gradient-to-br from-amber-900 to-amber-800 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">Tambah Meja Baru</h3>
                    <p class="text-amber-200 text-xs mt-1">Daftarkan nomor meja untuk sistem QR</p>
                </div>
                
                <div class="p-6">
                    <form action="/admin/tables" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nomor/Nama Meja</label>
                            <input type="text" name="number" 
                                   class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none transition placeholder-amber-200" 
                                   placeholder="Contoh: 01, 12, atau A1" required>
                            @error('number')
                                <p class="text-red-500 text-xs mt-2 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full bg-amber-800 text-white font-bold py-3 rounded-xl hover:bg-amber-900 shadow-lg shadow-amber-900/20 transition-all active:scale-95">
                            + Tambah Meja
                        </button>
                    </form>
                    
                    <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-100">
                        <div class="flex gap-3">
                            <span class="text-amber-600">💡</span>
                            <p class="text-xs text-amber-800 leading-relaxed font-medium">
                                Gunakan nomor meja yang unik. Setelah ditambahkan, sistem akan mengenerate QR Code otomatis untuk pelanggan scan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DAFTAR MEJA --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-amber-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-amber-50 flex justify-between items-center bg-white">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Daftar Meja Aktif</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Total {{ $tables->count() }} meja terdaftar</p>
                    </div>
                    @if($tables->count() > 0)
                    <a href="/admin/tables/print" target="_blank" class="bg-gray-800 text-white text-xs font-bold px-5 py-2.5 rounded-lg hover:bg-gray-700 transition flex items-center gap-2">
                        <span>🖨️</span> Cetak Semua QR
                    </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    @if($tables->isEmpty())
                        <div class="py-24 text-center">
                            <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-4xl">🪑</span>
                            </div>
                            <p class="text-gray-400 font-medium">Belum ada meja yang terdaftar.</p>
                        </div>
                    @else
                        <table class="w-full text-left">
                            <thead class="bg-amber-50/50 text-amber-900/50 text-xs uppercase tracking-wider border-b border-amber-50">
                                <tr>
                                    <th class="px-6 py-4 font-bold">No. Meja</th>
                                    <th class="px-6 py-4 font-bold">Tautan QR</th>
                                    <th class="px-6 py-4 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-amber-50">
                                @foreach($tables as $table)
                                <tr class="hover:bg-amber-50/30 transition">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center text-amber-900 font-black">
                                                {{ $table->number }}
                                            </div>
                                            <span class="font-bold text-gray-800">Meja {{ $table->number }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <code class="text-[11px] bg-white border border-amber-100 px-3 py-1.5 rounded-md text-amber-800 font-mono">
                                            /qr-menu?meja={{ $table->number }}
                                        </code>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <form action="/admin/tables/{{ $table->id }}" method="POST" class="inline" onsubmit="return confirm('Hapus meja ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600 font-bold text-xs p-2 hover:bg-red-50 rounded-lg transition">
                                                Hapus Meja
                                            </button>
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

    </div>
</div>

@endsection
