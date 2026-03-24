@extends('admin.layouts.app')
@section('header_title', 'Data Lembur Karyawan')
@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Daftar Pengajuan Lembur</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola dan setujui riwayat pengajuan lembur karyawan.</p>
    </div>
</div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama & NIK</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Bagian & Lokasi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Detail Pelaksanaan</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanda Tangan</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($overtimes as $ot)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $ot->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $ot->employee_name }}</div>
                            <div class="text-xs text-gray-500">{{ $ot->employee_no }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $ot->bagian }} ({{ $ot->sub_bagian }})</div>
                            <div class="text-xs text-gray-500">{{ $ot->divisi }} - {{ $ot->direktorat }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900 truncate max-w-xs">{{ $ot->nama_lemburan ?: '-' }}</div>
                            <div class="text-xs text-gray-500">Waktu: {{ $ot->jam_masuk ?: '-' }} - {{ $ot->jam_keluar ?: '-' }} ({{ $ot->total_jam ?: '0' }} Jam)</div>
                            <div class="text-xs text-gray-500">Tiket: {{ $ot->nomor_tiket ?: '-' }} | Req: {{ $ot->pemberi_lembur ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($ot->tanda_tangan)
                                <img src="{{ asset('storage/' . $ot->tanda_tangan) }}" alt="Tanda Tangan" class="h-10 mx-auto object-contain border rounded bg-white">
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($ot->status === 'approved')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                            @elseif($ot->status === 'rejected')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                            Belum ada pengajuan lembur.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
