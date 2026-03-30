@extends('admin.layouts.app')
@section('header_title', 'Edit Pengguna')
@section('content')

<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center mb-4">
        &larr; Kembali ke Daftar Pengguna
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Edit Data Pengguna</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    
    @if ($errors->any())
        <div class="bg-red-50 text-red-600 p-3 rounded mb-4 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username (Inisial)</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role/Peran</label>
            <select name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm bg-white">
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Karyawan (Input Lembur)</option>
                <option value="pimpinan" {{ $user->role == 'pimpinan' ? 'selected' : '' }}>Pimpinan (Bisa Approve)</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Kerja</label>
            <input type="text" name="lokasi_kerja" value="{{ old('lokasi_kerja', $user->lokasi_kerja) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Contoh: Jakarta / Head Office">
        </div>
        
        <div class="border-t border-gray-200 mt-6 pt-4">
            <h3 class="text-sm font-bold text-gray-800 mb-2">Ubah Password (Opsional)</h3>
            <p class="text-xs text-gray-500 mb-3">Kosongkan jika tidak ingin mengubah password.</p>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru Lokal</label>
                <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>
        </div>
        
        <div class="pt-4 flex justify-end space-x-3">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 font-medium text-sm transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium text-sm transition shadow-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection
