@extends('layouts.app')

@section('title', 'Kelola Menu')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">Total {{ $products->total() }} menu terdaftar</p>
    <a href="/admin/products/create" class="bg-amber-800 hover:bg-amber-900 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
        + Tambah Menu
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    @if($products->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <p>Belum ada menu. Tambahkan menu pertama!</p>
        </div>
    @else
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 border-b">
                <tr>
                    <th class="px-6 py-3 font-medium">Menu</th>
                    <th class="px-6 py-3 font-medium">Harga</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center text-sm text-gray-400">—</div>
                            @endif
                            <span class="font-medium text-gray-700">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($product->status === 'available')
                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">Tersedia</span>
                        @else
                            <span class="bg-red-100 text-red-600 text-xs font-semibold px-2 py-1 rounded-full">Habis</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="/admin/products/{{ $product->id }}/edit" class="text-amber-700 hover:underline font-medium mr-4">Edit</a>
                        <form method="POST" action="/admin/products/{{ $product->id }}" class="inline" onsubmit="return confirm('Hapus menu ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">
            {{ $products->links() }}
        </div>
    @endif
</div>

@endsection
