@extends('admin.layouts.app')
@section('header_title', 'Data Lembur Karyawan')
@section('content')

<form action="{{ route('admin.bulk_download') }}" method="POST" id="bulk-download-form">
@csrf
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 font-outfit uppercase tracking-tight">Daftar Pengajuan Lembur</h1>
    </div>
    <div class="flex space-x-2">
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white hover:bg-indigo-700 rounded-lg text-sm font-bold shadow-md transition-all border border-indigo-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Unduh Terpilih
        </button>
    </div>
</div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-center">
                            <input type="checkbox" id="select-all" class="rounded text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama & NIK</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bagian & Lokasi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Detail Pelaksanaan</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($overtimes as $ot)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            @if($ot->status === 'approved')
                                <input type="checkbox" name="ids[]" value="{{ $ot->id }}" class="row-checkbox rounded text-indigo-600 focus:ring-indigo-500">
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $ot->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $ot->employee_name }}</div>
                            <div class="text-xs text-gray-500">{{ $ot->employee_no }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $ot->bagian }}</div>
                            <div class="text-xs text-gray-500">{{ $ot->lokasi_kerja }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-bold text-gray-900 truncate max-w-xs uppercase tracking-tight">{{ $ot->nama_lemburan ?: '-' }}</div>
                            <div class="text-[10px] text-gray-600 font-bold mt-0.5">
                                {{ \Carbon\Carbon::parse($ot->tanggal_masuk)->format('d/m') }} ({{ $ot->jam_masuk }}) - 
                                {{ \Carbon\Carbon::parse($ot->tanggal_keluar)->format('d/m') }} ({{ $ot->jam_keluar }})
                            </div>
                            <div class="mt-1 text-[9px] text-indigo-600 font-extrabold uppercase bg-indigo-50 px-1.5 py-0.5 rounded-md inline-block">Total: {{ round($ot->total_jam) ?: '0' }} Jam</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($ot->status === 'approved')
                                <span class="px-2 inline-flex text-[10px] leading-5 font-bold uppercase tracking-wider rounded-full bg-green-100 text-green-800">Disetujui</span>
                            @elseif($ot->status === 'rejected')
                                <span class="px-2 inline-flex text-[10px] leading-5 font-bold uppercase tracking-wider rounded-full bg-red-100 text-red-800">Ditolak</span>
                            @else
                                <span class="px-2 inline-flex text-[10px] leading-5 font-bold uppercase tracking-wider rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-medium">
                            @if($ot->status === 'approved')
                            <a href="{{ route('overtime.print', $ot->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-md text-xs font-semibold transition-colors mt-1 border border-blue-100">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Cetak
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500 font-medium">
                            Belum ada pengajuan lembur yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const bulkBtn = document.querySelector('button[type="submit"]');

        function updateBtnState() {
            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            if (bulkBtn) {
                bulkBtn.disabled = checkedCount === 0;
                bulkBtn.style.opacity = checkedCount === 0 ? '0.5' : '1';
                bulkBtn.style.cursor = checkedCount === 0 ? 'not-allowed' : 'pointer';
            }
        }

        if (selectAll) {
            selectAll.addEventListener('click', function(event) {
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = event.target.checked;
                });
                updateBtnState();
            });
        }

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', updateBtnState);
        });

        // Initial state
        updateBtnState();
    });
</script>
@endsection
