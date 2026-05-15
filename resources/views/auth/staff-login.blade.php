<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login — Rasakopi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-amber-900 min-h-screen flex items-center justify-center">

<div class="w-full max-w-sm">
    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 mx-auto mb-3"
             onerror="this.style.display='none'">
        <h1 class="text-2xl font-bold text-white">Rasakopi</h1>
        <p class="text-amber-300 text-sm mt-1">Staff Panel</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-8">
        <h2 class="text-lg font-bold text-gray-700 mb-6">Masuk sebagai Staff</h2>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/staff/login">
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
    </div>

    <p class="text-center mt-6">
        <a href="/" class="text-amber-300 text-sm hover:text-white transition">← Kembali ke Website</a>
    </p>
</div>

</body>
</html>
