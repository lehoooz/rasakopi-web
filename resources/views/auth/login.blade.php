<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Rasakopi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-sm">
    <div class="text-center mb-8">
        <a href="/" class="flex items-center justify-center gap-2">
            
            <span class="text-2xl font-bold text-amber-800">Rasakopi</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-8">
        <h2 class="text-lg font-bold text-gray-700 mb-1">Selamat Datang!</h2>
        <p class="text-sm text-gray-400 mb-6">Masuk untuk melacak poin loyalitas kamu</p>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <button type="submit" class="w-full bg-amber-800 hover:bg-amber-900 text-white font-bold py-2.5 rounded-lg transition text-sm">
                Masuk
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-4">
            Belum punya akun?
            <a href="/register" class="text-amber-700 font-semibold hover:underline">Daftar Member</a>
        </p>
    </div>

    <p class="text-center mt-4">
        <a href="/" class="text-gray-400 text-sm hover:text-gray-600 transition">← Kembali ke Beranda</a>
    </p>
</div>

</body>
</html>
