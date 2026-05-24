<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rasakopi — @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

@auth
<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-amber-900 text-white flex flex-col flex-shrink-0">
        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-amber-700">
            <h1 class="text-2xl font-bold tracking-wide">Rasakopi</h1>
            <p class="text-amber-300 text-xs mt-1 capitalize">{{ Auth::user()->role }} Panel</p>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">

            @if(Auth::user()->role === 'admin')
                <a href="/admin" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-amber-700 transition {{ request()->is('admin') ? 'bg-amber-700' : '' }}">
                    Dashboard
                </a>
                <a href="/admin/products" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-amber-700 transition {{ request()->is('admin/products*') ? 'bg-amber-700' : '' }}">
                    Kelola Menu
                </a>
                <a href="/admin/users" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-amber-700 transition {{ request()->is('admin/users*') ? 'bg-amber-700' : '' }}">
                    Kelola Akun
                </a>
                <a href="/admin/reports" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-amber-700 transition {{ request()->is('admin/reports*') ? 'bg-amber-700' : '' }}">
                    Laporan Keuangan
                </a>
            @endif

            @if(Auth::user()->role === 'kasir')
                <a href="/kasir/pos" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-amber-700 transition {{ request()->is('kasir/pos*') ? 'bg-amber-700' : '' }}">
                    Kasir
                </a>
                <a href="/kasir" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-amber-700 transition {{ request()->is('kasir') ? 'bg-amber-700' : '' }}">
                    Riwayat Transaksi
                </a>
                <a href="/kasir/expenses" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-amber-700 transition {{ request()->is('kasir/expenses*') ? 'bg-amber-700' : '' }}">
                    Catat Pengeluaran
                </a>
            @endif

            @if(Auth::user()->role === 'customer')
                <a href="/customer" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-amber-700 transition {{ request()->is('customer') ? 'bg-amber-700' : '' }}">
                    Beranda
                </a>
                <a href="/customer/menu" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-amber-700 transition {{ request()->is('customer/menu*') ? 'bg-amber-700' : '' }}">
                    Katalog Menu
                </a>
                <a href="/customer/loyalty" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-amber-700 transition {{ request()->is('customer/loyalty*') ? 'bg-amber-700' : '' }}">
                    Poin Loyalitas
                </a>
            @endif

        </nav>

        {{-- User Info & Logout --}}
        <div class="px-4 py-4 border-t border-amber-700">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-amber-600 flex items-center justify-center font-bold text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-amber-300 text-xs truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-amber-700 transition text-sm">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Bar --}}
        <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between flex-shrink-0">
            <h2 class="text-lg font-semibold text-gray-700">@yield('title', 'Dashboard')</h2>
            <span class="text-sm text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</span>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

    </div>
</div>

@else
    {{-- Layout untuk halaman guest (login) --}}
    @yield('content')
@endauth

</body>
</html>
