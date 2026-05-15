@extends('layouts.app')

@section('title', 'Katalog Menu')

@section('content')

<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
    @forelse($products as $product)
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-36 object-cover">
        @else
            <div class="w-full h-36 bg-amber-50 flex items-center justify-center text-5xl">☕</div>
        @endif

        <div class="p-4">
            <p class="font-semibold text-gray-700 truncate">{{ $product->name }}</p>
            <p class="text-amber-700 font-bold mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <div class="mt-2">
                @if($product->status === 'available')
                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">Tersedia</span>
                @else
                    <span class="bg-red-100 text-red-600 text-xs font-semibold px-2 py-1 rounded-full">Habis</span>
                @endif
            </div>
        </div>
    </div>
    @empty
        <div class="col-span-4 text-center py-16 text-gray-400">
            <p class="text-4xl mb-3">☕</p>
            <p>Belum ada menu tersedia.</p>
        </div>
    @endforelse
</div>

@endsection
