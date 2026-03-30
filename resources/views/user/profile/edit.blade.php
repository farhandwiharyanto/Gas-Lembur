@extends(auth()->user()->role === 'pimpinan' ? 'pimpinan.layouts.app' : 'user.layouts.app')

@section('header_title', 'Profil Karyawan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl mx-auto">
    <div class="px-8 py-6 border-b border-gray-100">
        <h2 class="text-xl font-bold text-gray-800">Informasi Pribadi</h2>
        <p class="text-sm text-gray-500 mt-1">Perbarui data diri dan tanda tangan digital Anda untuk kebutuhan form lembur.</p>
    </div>

    <form id="profileForm" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Informasi:</strong> Selama mode LDAP nonaktif, Anda harus mengisi manual data Pribadi Anda di bawah ini agar formulir pengajuan berfungsi dengan baik.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Inisial / Username</label>
                <input type="text" value="{{ $user->username }}" readonly
                    class="w-full px-4 py-2 bg-gray-100 border border-gray-200 text-gray-500 rounded-lg outline-none cursor-not-allowed" title="Username/Inisial tidak dapat diubah">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 rounded-lg outline-none focus:ring-2 focus:border-blue-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">NIK (Employee No)</label>
                <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" required
                    class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 rounded-lg outline-none focus:ring-2 focus:border-blue-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 rounded-lg outline-none focus:ring-2 focus:border-blue-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Direktorat</label>
                <input type="text" name="direktorat" value="{{ old('direktorat', $user->direktorat) }}" required
                    class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 rounded-lg outline-none focus:ring-2 focus:border-blue-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Divisi</label>
                <input type="text" name="divisi" value="{{ old('divisi', $user->divisi) }}" required
                    class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 rounded-lg outline-none focus:ring-2 focus:border-blue-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Bagian</label>
                <input type="text" name="bagian" value="{{ old('bagian', $user->bagian) }}" required
                    class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 rounded-lg outline-none focus:ring-2 focus:border-blue-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Sub Bagian</label>
                <input type="text" name="sub_bagian" value="{{ old('sub_bagian', $user->sub_bagian) }}" required
                    class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 rounded-lg outline-none focus:ring-2 focus:border-blue-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Lokasi Kerja</label>
                <input type="text" name="lokasi_kerja" value="{{ old('lokasi_kerja', $user->lokasi_kerja) }}" required placeholder="Contoh: Jakarta / Head Office"
                    class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-900 rounded-lg outline-none focus:ring-2 focus:border-blue-500 transition-colors">
            </div>
        </div>

        <!-- Tanda Tangan Sections -->
        <div class="mt-8 pt-8 border-t border-gray-100">
            <h3 class="text-md font-bold text-gray-800 mb-4">Pengaturan Tanda Tangan</h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Tanda Tangan Terkini -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanda Tangan Saat Ini</label>
                    <div class="border border-gray-200 rounded-lg bg-gray-50 h-48 flex items-center justify-center p-4">
                        @if($user->tanda_tangan)
                            <img src="{{ Str::startsWith($user->tanda_tangan, 'data:') ? $user->tanda_tangan : asset($user->tanda_tangan) }}" alt="Tanda Tangan Anda" class="max-h-full max-w-full object-contain">
                        @else
                            <p class="text-gray-400 text-sm italic">Belum ada tanda tangan tersimpan.</p>
                        @endif
                    </div>
                </div>

                <!-- Update Tanda Tangan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Tanda Tangan Baru</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg bg-white relative p-6 flex flex-col items-center justify-center hover:border-indigo-400 transition-colors h-48">
                        <svg class="h-10 w-10 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <p class="text-sm text-gray-500 text-center mb-2">Pilih gambar file tanda tangan Anda dari komputer (PNG/JPG)</p>
                        <input type="file" name="tanda_tangan" accept="image/png, image/jpeg, image/jpg" class="block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:text-sm file:font-semibold
                          file:bg-indigo-50 file:text-indigo-700
                          hover:file:bg-indigo-100 cursor-pointer
                        ">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Pilih file tanda tangan (Maks 2MB). Kosongkan jika tidak ingin mengubah.</p>
                    @error('tanda_tangan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="pt-6 mt-6 flex justify-end">
            <button type="submit" 
                class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 text-white font-semibold rounded-lg text-sm transition-all shadow-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
