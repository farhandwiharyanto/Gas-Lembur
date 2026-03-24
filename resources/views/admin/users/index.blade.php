@extends('admin.layouts.app')
@section('header_title', 'Manajemen Pengguna')
@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Daftar Pengguna</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola akses akun karyawan dan admin.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah User -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Tambah Pengguna Baru</h2>
            
            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-3 rounded mb-4 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username (Inisial)</label>
                    <input type="text" name="username" value="{{ old('username') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Contoh: adm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Lokal</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <p class="text-xs text-gray-500 mt-1">Buat password minimal 6 karakter. Karyawan akan menggunakannya untuk Login.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role/Peran</label>
                    <select name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm bg-white">
                        <option value="user">Karyawan (Input Lembur)</option>
                        <option value="pimpinan">Pimpinan</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 text-white font-medium py-2 px-4 rounded-md hover:bg-blue-700 transition">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar User -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Username / Inisial</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Lengkap</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Terdaftar Pada</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $user->username }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($user->role === 'admin')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Admin</span>
                                @elseif($user->role === 'pimpinan')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Pimpinan</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">User</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded-md shadow-sm transition-colors text-xs font-semibold">
                                        Edit
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded-md shadow-sm transition-colors text-xs font-semibold">
                                            Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
