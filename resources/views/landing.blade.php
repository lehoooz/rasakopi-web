<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rasakopi — Kedai Kopi Terbaik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white font-sans antialiased">

{{-- NAVBAR --}}
<nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-sm shadow-sm">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">

        <a href="/" class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Rasakopi" class="h-9 w-auto"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
            <span class="text-xl font-bold text-amber-800">Rasakopi</span>
        </a>

        <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
            <a href="#beranda" class="hover:text-amber-800 transition">Beranda</a>
            <a href="#menu" class="hover:text-amber-800 transition">Menu</a>
            <a href="#tentang" class="hover:text-amber-800 transition">Tentang</a>
        </div>

        <div class="flex items-center gap-3">
            @auth
                @if(Auth::user()->role === 'customer')
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center gap-2 bg-amber-50 hover:bg-amber-100 px-4 py-2 rounded-full text-sm font-medium text-amber-800 transition">
                            <span>{{ Auth::user()->name }}</span>
                            <span class="bg-amber-200 text-amber-900 text-xs font-bold px-2 py-0.5 rounded-full">
                                {{ Auth::user()->cup_count }}/10 cup
                            </span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="member-dropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border p-4 z-50">
                            <p class="text-sm font-semibold text-gray-700 mb-1">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400 mb-3">{{ Auth::user()->email }}</p>

                            <div class="bg-amber-50 rounded-lg p-3 mb-3">
                                <p class="text-xs font-medium text-amber-800 mb-2">Program Loyalitas</p>
                                <div class="flex gap-1 mb-2">
                                    @for($i = 1; $i <= 10; $i++)
                                        <div class="flex-1 h-2 rounded-full {{ $i <= Auth::user()->cup_count ? 'bg-amber-600' : 'bg-amber-200' }}"></div>
                                    @endfor
                                </div>
                                @if(Auth::user()->cup_count >= 10)
                                    <p class="text-xs text-green-600 font-semibold">Kamu berhak 1 cup gratis!</p>
                                @else
                                    <p class="text-xs text-amber-700">{{ Auth::user()->cup_count }}/10 cup — butuh {{ 10 - Auth::user()->cup_count }} lagi</p>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left text-sm text-red-500 hover:text-red-700 transition">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @else
                <a href="/login" class="text-sm font-medium text-gray-600 hover:text-amber-800 transition">Masuk</a>
                <a href="/register" class="bg-amber-800 hover:bg-amber-900 text-white text-sm font-semibold px-4 py-2 rounded-full transition">
                    Daftar Member
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- HERO --}}
<section id="beranda" class="relative min-h-screen flex items-center">
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero.jpg') }}" alt="Hero" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/55"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-6 pt-20">
        @if(session('success'))
            <div class="mb-6 bg-green-500/90 text-white px-5 py-3 rounded-lg text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <p class="text-amber-300 font-medium tracking-widest text-sm uppercase mb-4">Selamat Datang di</p>
        <h1 class="text-5xl md:text-7xl font-bold text-white leading-tight mb-6">
            Rasa<span class="text-amber-400">kopi</span>
        </h1>
        <p class="text-gray-200 text-lg md:text-xl max-w-xl mb-8 leading-relaxed">
            Nikmati setiap tegukan kopi pilihan kami yang diseduh dengan penuh cinta. Dari biji terbaik, untuk momen terbaikmu.
        </p>
        <div class="flex flex-wrap gap-4">
            <a href="#menu" class="bg-amber-500 hover:bg-amber-400 text-white font-bold px-8 py-3 rounded-full transition text-sm">
                Lihat Menu
            </a>
            @guest
                <a href="/register" class="border-2 border-white text-white hover:bg-white hover:text-amber-900 font-bold px-8 py-3 rounded-full transition text-sm">
                    Jadi Member
                </a>
            @endguest
        </div>
    </div>
</section>

{{-- KEUNGGULAN --}}
<section class="py-16 bg-amber-50">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-6">
                <div class="w-12 h-12 bg-amber-200 rounded-full mb-4 mx-auto"></div>
                <h3 class="font-bold text-gray-800 text-lg mb-2">Biji Kopi Pilihan</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Kami hanya menggunakan biji kopi berkualitas tinggi yang dipilih langsung dari petani lokal terbaik.</p>
            </div>
            <div class="p-6">
                <div class="w-12 h-12 bg-amber-200 rounded-full mb-4 mx-auto"></div>
                <h3 class="font-bold text-gray-800 text-lg mb-2">Program Loyalitas</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Beli 10 cup, dapatkan 1 cup gratis! Daftar sebagai member dan mulai kumpulkan poinmu sekarang.</p>
            </div>
            <div class="p-6">
                <div class="w-12 h-12 bg-amber-200 rounded-full mb-4 mx-auto"></div>
                <h3 class="font-bold text-gray-800 text-lg mb-2">Suasana Nyaman</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Tempat yang hangat dan nyaman untuk bekerja, bersantai, atau sekadar menikmati waktu bersama.</p>
            </div>
        </div>
    </div>
</section>

{{-- MENU --}}
<section id="menu" class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-12">
            <p class="text-amber-600 font-semibold text-sm uppercase tracking-widest mb-2">Yang Kami Sajikan</p>
            <h2 class="text-4xl font-bold text-gray-800">Menu Kami</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
            <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 hover:-translate-y-1">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40 bg-amber-50 flex items-center justify-center text-gray-300 text-sm">Foto tidak tersedia</div>
                @endif
                <div class="p-4">
                    <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                    <p class="text-amber-700 font-bold mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center py-16 text-gray-400">
                <p>Menu sedang diperbarui.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- TENTANG --}}
<section id="tentang" class="py-20 bg-amber-900 text-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-amber-300 font-semibold text-sm uppercase tracking-widest mb-3">Cerita Kami</p>
                <h2 class="text-4xl font-bold mb-6">Tentang Rasakopi</h2>
                <p class="text-amber-100 leading-relaxed mb-4">
                    Rasakopi lahir dari kecintaan mendalam terhadap kopi dan keinginan untuk berbagi pengalaman minum kopi yang autentik kepada semua orang.
                </p>
                <p class="text-amber-100 leading-relaxed mb-6">
                    Setiap cangkir yang kami sajikan adalah hasil dari proses panjang — dari pemilihan biji, proses roasting, hingga teknik penyeduhan yang tepat. Kami percaya bahwa secangkir kopi yang baik bisa mengubah hari menjadi lebih bermakna.
                </p>
                <div class="flex gap-8">
                    <div>
                        <p class="text-3xl font-bold text-amber-300">3+</p>
                        <p class="text-amber-200 text-sm">Tahun Berdiri</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-amber-300">500+</p>
                        <p class="text-amber-200 text-sm">Member Setia</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-amber-300">10+</p>
                        <p class="text-amber-200 text-sm">Varian Menu</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl overflow-hidden shadow-2xl">
                <img src="{{ asset('images/hero.jpg') }}" alt="Tentang Rasakopi" class="w-full h-80 object-cover"
                     onerror="this.parentElement.innerHTML='<div class=\'w-full h-80 bg-amber-800\'></div>'">
            </div>
        </div>
    </div>
</section>

{{-- CTA MEMBER --}}
@guest
<section class="py-16 bg-amber-50">
    <div class="max-w-2xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-3">Jadi Member Rasakopi</h2>
        <p class="text-gray-500 mb-8">Daftar gratis dan mulai kumpulkan poin. Setiap 10 cup yang kamu beli, dapatkan 1 cup gratis!</p>
        <a href="/register" class="bg-amber-800 hover:bg-amber-900 text-white font-bold px-10 py-3 rounded-full transition text-sm">
            Daftar Sekarang — Gratis!
        </a>
    </div>
</section>
@endguest

{{-- FOOTER --}}
<footer class="bg-gray-900 text-gray-400 py-10">
    <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-7 w-auto opacity-80"
                 onerror="this.style.display='none'">
            <span class="text-white font-bold">Rasakopi</span>
        </div>
        <p class="text-sm">© {{ date('Y') }} Rasakopi. All rights reserved.</p>
        <p class="text-xs text-gray-600">
            <a href="/staff/login" class="hover:text-gray-400 transition">Staff</a>
        </p>
    </div>
</footer>

<script>
    function toggleDropdown() {
        const el = document.getElementById('member-dropdown');
        el.classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('member-dropdown');
        if (dropdown && !e.target.closest('[onclick="toggleDropdown()"]') && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>

</body>
</html>
