@extends('pimpinan.layouts.app')

@section('header_title', 'Manajemen Lembur')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-end">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800 font-outfit uppercase tracking-tight">Riwayat Pengajuan Lembur</h2>
            <p class="text-xs text-gray-400 mt-1 font-medium font-outfit uppercase tracking-wider italic">Halaman ini hanya untuk pemantauan data lembur yang telah diproses.</p>
        </div>
    </div>
    
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-6 mb-0 rounded" role="alert">
            <p class="font-bold">Berhasil</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-6 mb-0 rounded" role="alert">
            <p class="font-bold">Error</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="overflow-x-auto mt-6">
        <table class="w-full text-left text-sm text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold">TGL. PENGAJUAN</th>
                    <th scope="col" class="px-6 py-4 font-semibold">KARYAWAN</th>
                    <th scope="col" class="px-6 py-4 font-semibold">DETAIL PELAKSANAAN</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-center">STATUS</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($overtimes as $overtime)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-medium font-outfit">
                            {{ $overtime->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="block text-gray-800 font-bold font-outfit uppercase tracking-tight">{{ $overtime->employee_name }}</span>
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">NIK: {{ $overtime->employee_no }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900 font-outfit">{{ $overtime->nama_lemburan ?: '-' }}</div>
                            <div class="text-xs text-gray-600 font-bold">
                                {{ \Carbon\Carbon::parse($overtime->tanggal_masuk)->format('d M') }} ({{ $overtime->jam_masuk }}) s/d 
                                {{ \Carbon\Carbon::parse($overtime->tanggal_keluar)->format('d M') }} ({{ $overtime->jam_keluar }})
                            </div>
                            <div class="text-[10px] text-indigo-600 font-extrabold uppercase mt-1 tracking-tighter">Total: {{ round($overtime->total_jam) ?: '0' }} Jam</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-[10px] font-bold uppercase tracking-widest">
                            @if($overtime->status == 'waiting' || $overtime->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full border border-yellow-200">
                                    MENUNGGU
                                </span>
                            @elseif($overtime->status == 'approved')
                                <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full border border-emerald-200">
                                    DISETUJUI
                                </span>
                            @else
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full border border-red-200">
                                    DITOLAK
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center opacity-40">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-[11px] font-bold uppercase tracking-[0.2em]">Belum ada riwayat pengajuan lembur yang ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($overtimes->hasPages())
        <div class="px-8 py-4 border-t border-gray-100 bg-gray-50/30">
            {{ $overtimes->links() }}
        </div>
    @endif
</div>
@endsection
