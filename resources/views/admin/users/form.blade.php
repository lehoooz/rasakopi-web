@extends('layouts.app')

@section('title', isset($user) ? 'Edit Akun' : 'Tambah Akun')

@section('content')

<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">

        <form method="POST"
              action="{{ isset($user) ? '/admin/users/' . $user->id : '/admin/users' }}">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 @error('name') border-red-400 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 @error('email') border-red-400 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role / Peran</label>
                <select name="role" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kasir" {{ old('role', $user->role ?? '') === 'kasir' ? 'selected' : '' }}>Kasir</option>
                    <option value="customer" {{ old('role', $user->role ?? '') === 'customer' ? 'selected' : '' }}>Customer</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password @if(isset($user)) <span class="text-gray-400 font-normal">(kosongkan jika tidak ingin diubah)</span> @endif</label>
                <input type="password" name="password"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 @error('password') border-red-400 @enderror">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-amber-800 hover:bg-amber-900 text-white font-semibold px-5 py-2 rounded-lg text-sm transition">
                    {{ isset($user) ? 'Simpan Perubahan' : 'Tambah Akun' }}
                </button>
                <a href="/admin/users" class="px-5 py-2 rounded-lg border text-sm text-gray-600 hover:bg-gray-50 transition">Batal</a>
            </div>

        </form>
    </div>
</div>

@endsection
