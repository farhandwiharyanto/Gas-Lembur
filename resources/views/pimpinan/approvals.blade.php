@extends('pimpinan.layouts.app')

@section('header_title', 'Manajemen Lembur')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-end">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Menunggu Persetujuan Anda (Approvals)</h2>
            <p class="text-sm text-gray-500 mt-1">Antrean pengajuan lembur yang berada di Bagian fungsional Anda.</p>
        </div>
    </div>
    
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-6 mb-0 rounded" role="alert">
            <p class="font-bold">Berhasil</p>
            <p>{{ session('success') }}</p>
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
                    <th scope="col" class="px-6 py-4 font-semibold text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($overtimes as $overtime)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-medium">
                            {{ $overtime->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="block text-gray-800 font-medium">{{ $overtime->employee_name }}</span>
                            <span class="text-xs text-gray-400">NIK: {{ $overtime->employee_no }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $overtime->nama_lemburan ?: '-' }}</div>
                            <div class="text-xs text-gray-500">Wkt: {{ $overtime->jam_masuk ?: '-' }} s/d {{ $overtime->jam_keluar ?: '-' }} ({{ $overtime->total_jam ?: '0' }} Jam)</div>
                            <div class="text-xs text-gray-500 truncate max-w-xs">Tiket: {{ $overtime->nomor_tiket ?: '-' }} | Req: {{ $overtime->pemberi_lembur ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($overtime->status == 'waiting' || $overtime->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full border border-yellow-200 shadow-sm">
                                    Waiting
                                </span>
                            @elseif($overtime->status == 'approved')
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-full border border-emerald-200 shadow-sm">
                                    Approved
                                </span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-1 rounded-full border border-red-200 shadow-sm">
                                    Rejected
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center items-center space-x-2">
                                <form action="{{ route('pimpinan.approve', $overtime->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" onclick="return confirm('Setujui lemburan ini?')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-md shadow-sm transition-colors">Setuju</button>
                                </form>
                                <form action="{{ route('pimpinan.reject', $overtime->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" onclick="return confirm('Tolak lemburan ini?')" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md shadow-sm transition-colors">Tolak</button>
                                </form>
                                <a href="{{ route('overtime.print', $overtime->id) }}" target="_blank" class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 font-semibold rounded-md shadow-sm text-xs border border-blue-200 transition-colors">
                                    Cetak
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <p>Belum ada riwayat pengajuan lembur dari Karyawan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($overtimes->hasPages())
        <div class="px-8 py-4 border-t border-gray-100">
            {{ $overtimes->links() }}
        </div>
    @endif
</div>
@endsection
