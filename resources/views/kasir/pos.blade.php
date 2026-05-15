@extends('layouts.app')

@section('title', 'Buat Pesanan (POS)')

@section('content')

<div class="flex gap-6 h-full">

    {{-- KIRI: Grid Produk --}}
    <div class="flex-1">
        <p class="text-sm text-gray-500 mb-4">Klik produk untuk menambahkan ke keranjang</p>
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($products as $product)
            <button
                onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})"
                @if($product->status === 'sold_out') disabled @endif
                class="bg-white rounded-xl shadow-sm p-4 text-left transition hover:shadow-md hover:ring-2 hover:ring-amber-400
                       {{ $product->status === 'sold_out' ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">

                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-24 object-cover rounded-lg mb-3">
                @else
                    <div class="w-full h-24 bg-amber-50 rounded-lg flex items-center justify-center text-4xl mb-3">☕</div>
                @endif

                <p class="font-semibold text-gray-700 text-sm truncate">{{ $product->name }}</p>
                <p class="text-amber-700 font-bold text-sm mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                @if($product->status === 'sold_out')
                    <span class="text-xs text-red-500 font-medium">Habis</span>
                @endif
            </button>
            @endforeach
        </div>
    </div>

    {{-- KANAN: Keranjang --}}
    <div class="w-80 flex-shrink-0">
        <div class="bg-white rounded-xl shadow-sm p-5 sticky top-6">
            <h3 class="font-bold text-gray-700 text-base mb-4">Keranjang</h3>

            {{-- Pilih Member --}}
            <div class="mb-4">
                <label class="text-xs font-medium text-gray-500 mb-1 block">Member (opsional)</label>
                <select id="customer_id" onchange="updateLoyalty()" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <option value="">-- Pelanggan Umum --</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" data-cups="{{ $member->cup_count }}">
                            {{ $member->name }} ({{ $member->cup_count }}/10 ☕)
                        </option>
                    @endforeach
                </select>
                <p id="loyalty-info" class="text-xs text-amber-700 mt-1 hidden"></p>
            </div>

            {{-- Item Keranjang --}}
            <div id="cart-items" class="space-y-2 mb-4 min-h-[80px]">
                <p id="cart-empty" class="text-sm text-gray-400 text-center py-4">Keranjang kosong</p>
            </div>

            <hr class="mb-3">

            {{-- Ringkasan Harga --}}
            <div class="space-y-1 text-sm mb-4">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span id="subtotal-display">Rp 0</span>
                </div>
                <div class="flex justify-between text-red-500" id="discount-row" style="display:none!important">
                    <span>Diskon Loyalty</span>
                    <span id="discount-display">- Rp 0</span>
                </div>
                <div class="flex justify-between font-bold text-amber-800 text-base pt-1 border-t">
                    <span>Grand Total</span>
                    <span id="grandtotal-display">Rp 0</span>
                </div>
            </div>

            {{-- Form Submit --}}
            <form id="pos-form" method="POST" action="/kasir/pos">
                @csrf
                <input type="hidden" id="customer-input" name="customer_id" value="">
                <div id="items-input"></div>
                <button type="submit" id="btn-bayar"
                    class="w-full bg-amber-800 hover:bg-amber-900 text-white font-bold py-3 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    Proses Pembayaran
                </button>
            </form>
        </div>
    </div>

</div>

<script>
    let cart = {};
    let members = @json($members->keyBy('id'));

    function addToCart(id, name, price) {
        if (cart[id]) {
            cart[id].qty++;
        } else {
            cart[id] = { id, name, price, qty: 1 };
        }
        renderCart();
    }

    function changeQty(id, delta) {
        if (!cart[id]) return;
        cart[id].qty += delta;
        if (cart[id].qty <= 0) delete cart[id];
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cart-items');
        const empty = document.getElementById('cart-empty');
        const items = Object.values(cart);

        if (items.length === 0) {
            container.innerHTML = '<p id="cart-empty" class="text-sm text-gray-400 text-center py-4">Keranjang kosong</p>';
            updateTotals();
            return;
        }

        container.innerHTML = items.map(item => `
            <div class="flex items-center justify-between gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-700 truncate">${item.name}</p>
                    <p class="text-xs text-amber-700">Rp ${formatRp(item.price)}</p>
                </div>
                <div class="flex items-center gap-1">
                    <button onclick="changeQty(${item.id}, -1)" class="w-6 h-6 rounded bg-gray-100 hover:bg-gray-200 text-sm font-bold">−</button>
                    <span class="w-6 text-center text-sm font-semibold">${item.qty}</span>
                    <button onclick="changeQty(${item.id}, 1)" class="w-6 h-6 rounded bg-gray-100 hover:bg-gray-200 text-sm font-bold">+</button>
                </div>
            </div>
        `).join('');

        updateTotals();
    }

    function updateTotals() {
        const items = Object.values(cart);
        const subtotal = items.reduce((sum, i) => sum + i.price * i.qty, 0);
        const totalCups = items.reduce((sum, i) => sum + i.qty, 0);

        const customerId = document.getElementById('customer_id').value;
        const member = customerId ? members[customerId] : null;
        const currentCups = member ? member.cup_count : 0;

        let discount = 0;
        if (member && (currentCups + totalCups) >= 10) {
            const cheapest = items.reduce((min, i) => i.price < min.price ? i : min, items[0]);
            discount = cheapest ? cheapest.price : 0;
        }

        const grandTotal = subtotal - discount;

        document.getElementById('subtotal-display').textContent = 'Rp ' + formatRp(subtotal);
        document.getElementById('grandtotal-display').textContent = 'Rp ' + formatRp(grandTotal);

        const discountRow = document.getElementById('discount-row');
        if (discount > 0) {
            discountRow.style.removeProperty('display');
            document.getElementById('discount-display').textContent = '- Rp ' + formatRp(discount);
        } else {
            discountRow.style.setProperty('display', 'none', 'important');
        }

        // Update hidden inputs
        document.getElementById('customer-input').value = customerId;
        const itemsInput = document.getElementById('items-input');
        itemsInput.innerHTML = items.map((item, i) =>
            `<input type="hidden" name="items[${i}][id]" value="${item.id}">
             <input type="hidden" name="items[${i}][qty]" value="${item.qty}">`
        ).join('');

        document.getElementById('btn-bayar').disabled = items.length === 0;
    }

    function updateLoyalty() {
        const customerId = document.getElementById('customer_id').value;
        const info = document.getElementById('loyalty-info');
        const member = customerId ? members[customerId] : null;

        if (member) {
            const remaining = 10 - member.cup_count;
            if (member.cup_count >= 10) {
                info.textContent = '🎉 Member ini berhak dapat 1 cup gratis!';
            } else {
                info.textContent = `☕ ${member.cup_count}/10 cup — butuh ${remaining} cup lagi untuk gratis`;
            }
            info.classList.remove('hidden');
        } else {
            info.classList.add('hidden');
        }

        updateTotals();
    }

    function formatRp(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
</script>

@endsection
