@extends('layouts.app')

@section('title', isset($product) ? 'Edit Menu' : 'Tambah Menu')

@section('content')

<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">

        <form method="POST"
              action="{{ isset($product) ? '/admin/products/' . $product->id : '/admin/products' }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Menu</label>
                <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 @error('name') border-red-400 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 @error('price') border-red-400 @enderror">
                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <option value="available" {{ old('status', $product->status ?? '') === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="sold_out" {{ old('status', $product->status ?? '') === 'sold_out' ? 'selected' : '' }}>Habis</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Menu (opsional)</label>
                @if(isset($product) && $product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-20 h-20 rounded-lg object-cover mb-2">
                @endif
                <input type="file" name="image" accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200">
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-amber-800 hover:bg-amber-900 text-white font-semibold px-5 py-2 rounded-lg text-sm transition">
                    {{ isset($product) ? 'Simpan Perubahan' : 'Tambah Menu' }}
                </button>
                <a href="/admin/products" class="px-5 py-2 rounded-lg border text-sm text-gray-600 hover:bg-gray-50 transition">Batal</a>
            </div>

        </form>
    </div>
</div>

@endsection
