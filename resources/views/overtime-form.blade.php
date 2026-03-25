@extends('user.layouts.app')

@section('header_title', 'Buat Pengajuan Lembur')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl mx-auto">
    <div class="px-8 py-6 border-b border-gray-100 text-center">
        <h2 class="text-2xl font-bold text-gray-800">{{ isset($overtime) ? 'Edit Pengajuan Lembur' : 'Formulir Lembur' }}</h2>
        <p class="text-sm text-gray-500 mt-2">Data diri Anda ditarik otomatis dari profil. Lengkapi detail pekerjaan lembur di bawah ini.</p>
    </div>

    <!-- Peringatan kelengkapan profil -->
    @if(!auth()->user()->tanda_tangan)
        <div class="mx-8 mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Profil Anda belum lengkap. Silakan lengkapi tanda tangan di halaman <a href="{{ route('user.profile.edit') }}" class="font-bold underline">Profil Karyawan</a> sebelum mengajukan lembur.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form id="overtimeForm" action="{{ isset($overtime) ? route('overtime.update', $overtime->id) : route('overtime.store') }}" method="POST" class="p-8 space-y-8">
        @csrf
        @if(isset($overtime)) @method('PUT') @endif
        
        <!-- Bagian Data Diri (Read Only) -->
        <fieldset class="border border-gray-200 rounded-xl p-6 bg-gray-50/50 relative">
            <legend class="text-sm font-semibold text-gray-600 bg-white px-3 py-1 rounded-md border border-gray-200 shadow-sm ml-4">Data Diri (Otomatis dari Profil/SNI)</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-2">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="employee_name" value="{{ auth()->user()->name }}" readonly
                        class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">NIK</label>
                    <input type="text" name="employee_no" value="{{ auth()->user()->nik }}" readonly
                        class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Direktorat</label>
                    <input type="text" name="direktorat" value="{{ auth()->user()->direktorat }}" readonly
                        class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Divisi</label>
                    <input type="text" name="divisi" value="{{ auth()->user()->divisi }}" readonly
                        class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Bagian</label>
                    <input type="text" name="bagian" value="{{ auth()->user()->bagian }}" readonly
                        class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Sub Bagian</label>
                    <input type="text" name="sub_bagian" value="{{ auth()->user()->sub_bagian }}" readonly
                        class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                </div>
            </div>
        </fieldset>

        <!-- Detail Pekerjaan Lembur -->
        <fieldset class="border border-gray-200 rounded-xl p-6 relative">
            <legend class="text-sm font-semibold text-gray-600 bg-white px-3 py-1 rounded-md border border-gray-200 shadow-sm ml-4">Detail Pelaksanaan Lembur</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lemburan</label>
                    <input type="text" name="nama_lemburan" value="{{ old('nama_lemburan', $overtime->nama_lemburan ?? '') }}" required placeholder="Contoh: Maintenance Server"
                        class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Masuk</label>
                    <input type="time" name="jam_masuk" id="jam_masuk" value="{{ old('jam_masuk', $overtime->jam_masuk ?? '') }}" required
                        class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Keluar</label>
                    <input type="time" name="jam_keluar" id="jam_keluar" value="{{ old('jam_keluar', $overtime->jam_keluar ?? '') }}" required
                        class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Total Jam Lembur</label>
                    <input type="text" name="total_jam" id="total_jam" value="{{ old('total_jam', $overtime->total_jam ?? '') }}" required readonly placeholder="0.00"
                        class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed font-mono">
                </div>
                <div class="hidden md:block"></div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Tiket</label>
                    <input type="text" name="nomor_tiket" value="{{ old('nomor_tiket', $overtime->nomor_tiket ?? '') }}" required placeholder="Contoh: INC001122"
                        class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pemberi Lembur</label>
                    <select name="pemberi_lembur" required class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-colors">
                        <option value="" disabled {{ !isset($overtime) ? 'selected' : '' }}>Pilih Pemberi Lembur</option>
                        <option value="ARIE ANDRIAN" {{ (isset($overtime) && $overtime->pemberi_lembur == 'ARIE ANDRIAN') ? 'selected' : '' }}>ARIE ANDRIAN</option>
                        <option value="SURYO HARTANTO" {{ (isset($overtime) && $overtime->pemberi_lembur == 'SURYO HARTANTO') ? 'selected' : '' }}>SURYO HARTANTO</option>
                        <option value="MUHAMMAD RIZQI ANDRIAN" {{ (isset($overtime) && $overtime->pemberi_lembur == 'MUHAMMAD RIZQI ANDRIAN') ? 'selected' : '' }}>MUHAMMAD RIZQI ANDRIAN</option>
                        <option value="MUH. GHOFARUDIN FALAH" {{ (isset($overtime) && $overtime->pemberi_lembur == 'MUH. GHOFARUDIN FALAH') ? 'selected' : '' }}>MUH. GHOFARUDIN FALAH</option>
                    </select>
                </div>
            </div>
        </fieldset>

        <!-- Tanda Tangan Info -->
        <fieldset class="border border-gray-200 rounded-xl p-6 relative">
            <legend class="text-sm font-semibold text-gray-600 bg-white px-3 py-1 rounded-md border border-gray-200 shadow-sm ml-4">Tanda Tangan</legend>
            <div class="mt-2 text-center">
                @if(auth()->user()->tanda_tangan)
                    <div class="inline-block border border-gray-200 rounded-lg p-2 bg-white shadow-sm mb-3">
                        <img src="{{ auth()->user()->tanda_tangan }}" alt="Tanda Tangan" class="h-32 object-contain">
                    </div>
                    <p class="text-sm text-green-600 font-medium flex items-center justify-center">
                        <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Tanda tangan otomatis ditarik dari profil Anda.
                    </p>
                    <input type="hidden" name="tanda_tangan" value="{{ auth()->user()->tanda_tangan }}">
                @else
                    <div class="p-4 border-2 border-dashed border-red-200 rounded-lg bg-red-50">
                        <p class="text-red-600 text-sm font-medium mb-3">Anda belum memiliki tanda tangan di profil.</p>
                        <!-- Canvas fallback for those who didn't set up profile -->
                        <div class="bg-white border rounded relative overflow-hidden hidden" id="canvas-container">
                            <canvas id="signature-pad" class="w-full h-40 cursor-crosshair touch-none" width="800" height="150"></canvas>
                        </div>
                        <p class="text-xs text-red-500 mt-2">Harap simpan profil Anda terlebih dahulu untuk memunculkan tanda tangan permanen, atau hubungi admin.</p>
                        
                        <input type="hidden" name="tanda_tangan" id="tanda_tangan_fallback" value="">
                    </div>
                @endif
            </div>
        </fieldset>

        <div class="pt-4 flex justify-end">
            @if(auth()->user()->tanda_tangan)
                <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 focus:ring-4 focus:ring-indigo-200">
                    {{ isset($overtime) ? 'Simpan Perubahan' : 'Kirim Pengajuan Lembur' }}
                </button>
            @else
                <button type="button" disabled class="px-8 py-3 bg-gray-400 text-white font-bold rounded-xl shadow cursor-not-allowed opacity-70">
                    Lengkapi Profil Dahulu
                </button>
            @endif
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jamMasuk = document.getElementById('jam_masuk');
    const jamKeluar = document.getElementById('jam_keluar');
    const totalJam = document.getElementById('total_jam');

    function calculateTotal() {
        if (jamMasuk.value && jamKeluar.value) {
            let masuk = new Date('1970-01-01T' + jamMasuk.value + ':00');
            let keluar = new Date('1970-01-01T' + jamKeluar.value + ':00');
            
            // Handle cross midnight
            if (keluar < masuk) {
                keluar.setDate(keluar.getDate() + 1);
            }
            
            let diffHours = (keluar - masuk) / (1000 * 60 * 60);
            totalJam.value = diffHours.toFixed(2);
        } else {
            totalJam.value = '';
        }
    }

    jamMasuk.addEventListener('change', calculateTotal);
    jamKeluar.addEventListener('change', calculateTotal);
});
</script>
@endpush
