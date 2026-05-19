<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Member — Rasakopi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-amber-900 min-h-screen flex items-center justify-center">

<div class="w-full max-w-sm">
    {{-- Logo & Brand Header --}}
    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 mx-auto mb-3"
             onerror="this.style.display='none'">
        <h1 class="text-2xl font-bold text-white">Rasakopi</h1>
        <p class="text-amber-300 text-sm mt-1">Nikmati citarasa kopi terbaik dengan pelayanan terpadu</p>
    </div>

    {{-- Register Card --}}
    <div class="bg-white rounded-2xl shadow-2xl p-8">
        <h2 class="text-lg font-bold text-gray-700 mb-1">Daftar Member</h2>
        <p class="text-sm text-gray-400 mb-6">Mulai kumpulkan poin loyalitas Anda</p>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg mb-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Register --}}
        <form method="POST" action="/register">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <button type="submit" class="w-full bg-amber-800 hover:bg-amber-900 text-white font-bold py-2.5 rounded-lg transition text-sm">
                Daftar Sekarang
            </button>
        </form>

        {{-- Tautan Login --}}
        <p class="text-center text-sm text-gray-500 mt-6">
            Sudah punya akun?
            <a href="/login" class="text-amber-700 font-semibold hover:underline">Masuk</a>
        </p>
    </div>

    <p class="text-center mt-6">
        <a href="/" class="text-amber-300 text-sm hover:text-white transition">&larr; Kembali ke Beranda</a>
    </p>
</div>

</body>
</html>
