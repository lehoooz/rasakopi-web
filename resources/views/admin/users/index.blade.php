@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">Total {{ $users->total() }} akun terdaftar</p>
    <a href="/admin/users/create" class="bg-amber-800 hover:bg-amber-900 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
        + Tambah Akun
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    @if($users->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <p>Belum ada data akun.</p>
        </div>
    @else
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 border-b">
                <tr>
                    <th class="px-6 py-3 font-medium">Nama</th>
                    <th class="px-6 py-3 font-medium">Email</th>
                    <th class="px-6 py-3 font-medium">Role</th>
                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-700">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @if($user->role === 'admin')
                            <span class="bg-purple-100 text-purple-700 text-xs font-semibold px-2 py-1 rounded-full">Admin</span>
                        @elseif($user->role === 'kasir')
                            <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-1 rounded-full">Kasir</span>
                        @else
                            <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-1 rounded-full">Customer</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="/admin/users/{{ $user->id }}/edit" class="text-amber-700 hover:underline font-medium mr-4">Edit</a>
                        <form method="POST" action="/admin/users/{{ $user->id }}" class="inline" onsubmit="return confirm('Hapus akun ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">
            {{ $users->links() }}
        </div>
    @endif
</div>

@endsection
