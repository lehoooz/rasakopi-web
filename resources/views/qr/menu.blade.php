<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu Digital – RasaKopi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .loading { opacity: 0.5; pointer-events: none; }
    </style>
</head>
<body class="bg-[#FFF8F0] text-[#1C1009] antialiased min-h-screen pb-32">

    {{-- HEADER PREMIUM (Senada dengan Sidebar Admin/Kasir) --}}
    <header class="bg-gradient-to-br from-[#2C1A0E] to-[#5C3317] pt-8 pb-12 px-6 sticky top-0 z-50 shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#FBBF24] rounded-xl flex items-center justify-center text-xl shadow-inner">☕</div>
                <h1 class="text-white font-black text-xl tracking-tight">RasaKopi</h1>
            </div>
            <div class="bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/20">
                <span class="text-white text-xs font-bold tracking-wider uppercase">Meja {{ $tableNumber }}</span>
            </div>
        </div>
        <div class="flex flex-col">
            <h2 class="text-white/60 text-xs font-bold uppercase tracking-widest mb-1">Selamat Datang</h2>
            <h3 class="text-white text-2xl font-extrabold">Mau ngopi apa hari ini?</h3>
        </div>
    </header>

    {{-- DAFTAR MENU --}}
    <main class="px-6 -mt-6">
        <div class="grid grid-cols-2 gap-4">
            @foreach ($products as $product)
                @php $inCart = $cart[$product->id] ?? null; @endphp
                <div class="bg-white rounded-3xl p-3 shadow-md border border-[#F0E0D0] flex flex-col h-full" id="product-{{ $product->id }}">
                    {{-- Image --}}
                    <div class="w-full h-32 bg-[#FFF0DC] rounded-2xl overflow-hidden mb-3 relative">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl">☕</div>
                        @endif
                    </div>
                    
                    {{-- Info --}}
                    <div class="flex-1 px-1">
                        <h4 class="font-extrabold text-sm text-[#2C1A0E] mb-1 line-clamp-2 leading-tight h-8">{{ $product->name }}</h4>
                        <p class="text-[#5C3317] font-black text-sm mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>

                    {{-- Action Button --}}
                    <div class="action-wrapper">
                        @if($inCart)
                            <div class="flex items-center justify-between bg-[#FFF8F0] rounded-xl p-1 border border-[#F0E0D0]">
                                <button onclick="updateCart({{ $product->id }}, 'remove')" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm font-bold text-[#2C1A0E] transition-active active:scale-90">−</button>
                                <span class="font-extrabold text-sm text-[#2C1A0E]">{{ $inCart['quantity'] }}</span>
                                <button onclick="updateCart({{ $product->id }}, 'add')" class="w-8 h-8 flex items-center justify-center bg-[#2C1A0E] rounded-lg shadow-sm font-bold text-white transition-active active:scale-90">+</button>
                            </div>
                        @else
                            <button onclick="updateCart({{ $product->id }}, 'add')" class="w-full py-2.5 bg-[#2C1A0E] text-white rounded-xl text-xs font-extrabold shadow-lg shadow-[#2C1A0E]/20 transition-all active:scale-95">
                                + Tambah
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    {{-- FLOATING CART (Matching Kasir Grid) --}}
    <div class="fixed bottom-6 left-6 right-6 transition-all duration-300 transform {{ $totalItems > 0 ? 'translate-y-0 opacity-100' : 'translate-y-20 opacity-0 pointer-events-none' }}" id="cart-bar-wrap">
        <a href="/qr-menu/checkout" class="bg-[#2C1A0E] rounded-3xl p-4 flex items-center justify-between shadow-2xl shadow-[#2C1A0E]/40 border border-white/10 ring-4 ring-[#2C1A0E]/10">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-[#FBBF24] rounded-2xl flex items-center justify-center text-[#2C1A0E] font-black text-sm" id="bar-count">
                    {{ $totalItems }}
                </div>
                <div>
                    <p class="text-white text-sm font-extrabold">Lihat Pesanan</p>
                    <p class="text-white/50 text-[10px] font-bold uppercase tracking-widest">Selesaikan Pembayaran</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[#FBBF24] font-black text-lg" id="bar-total">Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
            </div>
        </a>
    </div>

    <script>
        async function updateCart(productId, action) {
            const wrapper = document.querySelector(`#product-${productId} .action-wrapper`);
            wrapper.classList.add('loading');
            
            const url = action === 'add' ? '/qr-menu/cart/add' : '/qr-menu/cart/remove';
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ product_id: productId })
                });

                const data = await response.json();
                
                if (data.status === 'success') {
                    // Update Product UI
                    if (data.currentQty > 0) {
                        wrapper.innerHTML = `
                            <div class="flex items-center justify-between bg-[#FFF8F0] rounded-xl p-1 border border-[#F0E0D0]">
                                <button onclick="updateCart(${productId}, 'remove')" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm font-bold text-[#2C1A0E] transition-active active:scale-90">−</button>
                                <span class="font-extrabold text-sm text-[#2C1A0E]">${data.currentQty}</span>
                                <button onclick="updateCart(${productId}, 'add')" class="w-8 h-8 flex items-center justify-center bg-[#2C1A0E] rounded-lg shadow-sm font-bold text-white transition-active active:scale-90">+</button>
                            </div>
                        `;
                    } else {
                        wrapper.innerHTML = `
                            <button onclick="updateCart(${productId}, 'add')" class="w-full py-2.5 bg-[#2C1A0E] text-white rounded-xl text-xs font-extrabold shadow-lg shadow-[#2C1A0E]/20 transition-all active:scale-95">
                                + Tambah
                            </button>
                        `;
                    }

                    // Update Floating Bar
                    const bar = document.getElementById('cart-bar-wrap');
                    if (data.totalItems > 0) {
                        document.getElementById('bar-count').innerText = data.totalItems;
                        document.getElementById('bar-total').innerText = 'Rp ' + data.totalPrice.toLocaleString('id-ID');
                        bar.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
                        bar.classList.add('translate-y-0', 'opacity-100');
                    } else {
                        bar.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
                        bar.classList.remove('translate-y-0', 'opacity-100');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                wrapper.classList.remove('loading');
            }
        }
    </script>
</body>
</html>
