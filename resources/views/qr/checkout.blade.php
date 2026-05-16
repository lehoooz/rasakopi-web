<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Checkout – RasaKopi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#FFF8F0] text-[#1C1009] antialiased min-h-screen pb-12">

    {{-- HEADER PREMIUM (Matching Menu) --}}
    <header class="bg-gradient-to-br from-[#2C1A0E] to-[#5C3317] pt-8 pb-12 px-6 sticky top-0 z-50 shadow-lg">
        <div class="flex items-center gap-4 mb-4">
            <a href="/qr-menu" class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-white border border-white/20">←</a>
            <h1 class="text-white font-black text-xl tracking-tight">Konfirmasi Pesanan</h1>
        </div>
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-white/60 text-xs font-bold uppercase tracking-widest mb-1">Meja Anda</h2>
                <h3 class="text-white text-lg font-extrabold">Meja {{ $tableNumber }}</h3>
            </div>
            <div class="bg-[#FBBF24] px-4 py-1.5 rounded-full shadow-lg shadow-amber-900/40">
                <span class="text-[#2C1A0E] text-xs font-black tracking-wider uppercase">V-Pay / QRIS</span>
            </div>
        </div>
    </header>

    <main class="px-6 -mt-6 space-y-6">

        {{-- ORDER SUMMARY CARD --}}
        <div class="bg-white rounded-3xl p-6 shadow-md border border-[#F0E0D0]">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 bg-[#FFF0DC] rounded-lg flex items-center justify-center">🧾</div>
                <h4 class="font-extrabold text-[#2C1A0E]">Rincian Pesanan</h4>
            </div>

            <div class="space-y-4 mb-6">
                @foreach ($cart as $id => $item)
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 bg-[#FFF0DC] text-[#5C3317] font-black text-[11px] flex items-center justify-center rounded-lg">{{ $item['quantity'] }}x</span>
                            <span class="text-sm font-bold text-[#2C1A0E]">{{ $item['name'] }}</span>
                        </div>
                        <span class="text-sm font-bold text-[#5C3317]">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-[#F0E0D0] pt-4 space-y-2">
                <div class="flex justify-between text-xs font-bold text-gray-400">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                @if($discountAmount > 0)
                <div class="flex justify-between text-xs font-bold text-green-600">
                    <span>Loyalty Discount (Gratis 1)</span>
                    <span>- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center pt-2">
                    <span class="text-sm font-black text-[#2C1A0E]">Total Bayar</span>
                    <span class="text-xl font-black text-[#5C3317]">Rp {{ number_format($totalPrice - $discountAmount, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- LOYALTY PROGRESS --}}
            @auth
            @if(auth()->user()->role === 'customer')
            <div class="mt-6 p-4 bg-amber-50 rounded-2xl border border-amber-100">
                <div class="flex justify-between items-center mb-2 text-[11px] font-black uppercase tracking-wider text-amber-800/60">
                    <span>Loyalty Progress</span>
                    <span>{{ auth()->user()->cup_count }}/10 Cup</span>
                </div>
                <div class="w-full h-2.5 bg-white rounded-full overflow-hidden border border-amber-100">
                    <div class="h-full bg-[#F59E0B] rounded-full" style="width: {{ (auth()->user()->cup_count / 10) * 100 }}%"></div>
                </div>
                @if($discountAmount > 0)
                    <p class="text-[10px] text-green-600 font-extrabold mt-3 italic">🎉 Reward Aktif: Gratis 1 Cup untuk pesanan ini!</p>
                @else
                    <p class="text-[10px] text-amber-700 font-bold mt-3">Kurang {{ 10 - auth()->user()->cup_count }} cup lagi untuk dapat gratisan!</p>
                @endif
            </div>
            @endif
            @endauth
        </div>

        {{-- IDENTITY CARD --}}
        <div class="bg-white rounded-3xl shadow-md border border-[#F0E0D0] overflow-hidden">
            <div class="flex border-b border-[#F0E0D0]">
                <button id="tab-guest" onclick="switchIdentity('guest')" class="flex-1 py-4 text-xs font-black uppercase tracking-widest text-center transition-all {{ auth()->check() ? 'text-gray-300' : 'bg-white text-[#2C1A0E] border-b-2 border-[#2C1A0E]' }}">
                    👤 Guest
                </button>
                <button id="tab-member" onclick="switchIdentity('member')" class="flex-1 py-4 text-xs font-black uppercase tracking-widest text-center transition-all {{ auth()->check() ? 'bg-white text-[#2C1A0E] border-b-2 border-[#2C1A0E]' : 'text-gray-300' }}">
                    🎖️ Member
                </button>
            </div>

            <div class="p-6">
                {{-- Content Guest --}}
                <div id="content-guest" class="{{ auth()->check() ? 'hidden' : '' }}">
                    <p class="text-xs text-gray-400 font-bold mb-4">Pesan cepat sebagai tamu tanpa perlu login.</p>
                    <form action="/qr-menu/checkout" method="POST" id="form-guest" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-[10px] font-black text-[#5C3317] uppercase mb-1.5 block">Nama Anda (Opsional)</label>
                            <input type="text" name="customer_name" class="w-full px-4 py-3 bg-[#FFF8F0] border border-[#F0E0D0] rounded-xl text-sm font-bold outline-none focus:ring-2 focus:ring-[#2C1A0E]" placeholder="Kosongkan untuk Pelanggan Umum...">
                        </div>
                    </form>
                </div>

                {{-- Content Member --}}
                <div id="content-member" class="{{ auth()->check() ? '' : 'hidden' }}">
                    @auth
                        <div class="flex items-center gap-4 bg-green-50 p-4 rounded-2xl border border-green-100">
                            <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white font-black text-xl">{{ substr(auth()->user()->name, 0, 1) }}</div>
                            <div>
                                <p class="font-extrabold text-[#2C1A0E]">{{ auth()->user()->name }}</p>
                                <p class="text-[10px] text-green-600 font-bold uppercase tracking-widest">Akun Member Aktif</p>
                            </div>
                        </div>
                        <form action="/qr-menu/checkout" method="POST" id="form-member">
                            @csrf
                            <input type="hidden" name="customer_name" value="{{ auth()->user()->name }}">
                        </form>
                    @else
                        <div class="text-center py-2">
                            <p class="text-xs text-gray-400 font-bold mb-6">Kumpulkan poin loyalitas dengan login akun member kamu.</p>
                            <a href="/login?redirect=qr-checkout" class="block w-full py-3 bg-white border-2 border-[#2C1A0E] text-[#2C1A0E] rounded-2xl text-xs font-black shadow-lg shadow-[#2C1A0E]/5 active:scale-95 transition-all">🔑 Masuk Member</a>
                            <p class="text-[10px] text-gray-300 mt-4">Belum punya akun? <a href="/register?redirect=qr-checkout" class="text-[#5C3317] font-black underline">Daftar Sekarang</a></p>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        {{-- PAYMENT CARD --}}
        <div class="bg-white rounded-3xl p-6 shadow-md border border-[#F0E0D0]">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 bg-[#FFF0DC] rounded-lg flex items-center justify-center">💳</div>
                <h4 class="font-extrabold text-[#2C1A0E]">Metode Pembayaran</h4>
            </div>
            
            <div class="bg-gradient-to-br from-[#1a1a2e] to-[#16213e] rounded-2xl p-6 text-center shadow-inner">
                <div class="bg-white rounded-xl p-6 mb-4 inline-block shadow-lg">
                    <span class="text-5xl block mb-2">▣</span>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">QRIS - Scan & Pay</p>
                </div>
                <p class="text-white/80 text-xs font-bold leading-relaxed mb-4">Gunakan DANA, OVO, GoPay atau<br>M-Banking favorit Anda.</p>
                <div class="inline-block bg-[#FBBF24]/20 text-[#FBBF24] px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider border border-[#FBBF24]/20">
                    ⚡ Demo Mode — Bayar di Tempat
                </div>
            </div>
        </div>

        {{-- SUBMIT BUTTON --}}
        <button type="button" onclick="submitFinalOrder()" class="w-full py-5 bg-gradient-to-br from-[#2C1A0E] to-[#5C3317] text-white rounded-3xl text-lg font-black shadow-2xl shadow-[#2C1A0E]/40 active:scale-95 transition-all">
            ☕ Konfirmasi & Pesan!
        </button>

    </main>

    <script>
        function switchIdentity(type) {
            const tGuest = document.getElementById('tab-guest');
            const tMember = document.getElementById('tab-member');
            const cGuest = document.getElementById('content-guest');
            const cMember = document.getElementById('content-member');

            if (type === 'guest') {
                tGuest.classList.add('bg-white', 'text-[#2C1A0E]', 'border-b-2', 'border-[#2C1A0E]');
                tGuest.classList.remove('text-gray-300');
                tMember.classList.add('text-gray-300');
                tMember.classList.remove('bg-white', 'text-[#2C1A0E]', 'border-b-2', 'border-[#2C1A0E]');
                cGuest.classList.remove('hidden');
                cMember.classList.add('hidden');
            } else {
                tMember.classList.add('bg-white', 'text-[#2C1A0E]', 'border-b-2', 'border-[#2C1A0E]');
                tMember.classList.remove('text-gray-300');
                tGuest.classList.add('text-gray-300');
                tGuest.classList.remove('bg-white', 'text-[#2C1A0E]', 'border-b-2', 'border-[#2C1A0E]');
                cMember.classList.remove('hidden');
                cGuest.classList.add('hidden');
            }
        }

        function submitFinalOrder() {
            const isTabMember = document.getElementById('tab-member').classList.contains('border-b-2');
            if (isTabMember) {
                @auth
                    document.getElementById('form-member').submit();
                @else
                    alert('Silakan login terlebih dahulu untuk memesan sebagai Member.');
                @endauth
            } else {
                document.getElementById('form-guest').submit();
            }
        }
    </script>

</body>
</html>
