@extends('pimpinan.layouts.app')

@section('header_title', 'Manajemen Lembur')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-end">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800 font-outfit uppercase tracking-tight">Menunggu Approval Pimpinan</h2>
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
                            <div class="text-xs text-gray-600 font-bold">
                                {{ \Carbon\Carbon::parse($overtime->tanggal_masuk)->format('d M') }} ({{ $overtime->jam_masuk }}) s/d 
                                {{ \Carbon\Carbon::parse($overtime->tanggal_keluar)->format('d M') }} ({{ $overtime->jam_keluar }})
                            </div>
                            <div class="text-[11px] text-indigo-600 font-extrabold uppercase mt-1 tracking-tighter">Total: {{ round($overtime->total_jam) ?: '0' }} Jam</div>
                            <div class="text-[10px] text-gray-400 truncate max-w-xs uppercase mt-1 italic">Tiket: {{ $overtime->nomor_tiket ?: '-' }} | Req: {{ $overtime->pemberi_lembur ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-[10px] font-bold uppercase tracking-widest">
                            @if($overtime->status == 'waiting' || $overtime->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-2.5 py-1 rounded-full border border-yellow-200">
                                    MENUNGGU
                                </span>
                            @elseif($overtime->status == 'approved')
                                <span class="bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-full border border-emerald-200">
                                    DISETUJUI
                                </span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2.5 py-1 rounded-full border border-red-200">
                                    DITOLAK
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-bold uppercase tracking-widest">
                            <div class="flex justify-center flex-wrap items-center gap-2">
                                <form action="{{ route('pimpinan.approve', $overtime->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" onclick="return confirm('Setujui lemburan ini?')" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-extrabold rounded-lg shadow-sm border border-emerald-500 transition-all active:scale-95">SETUJU</button>
                                </form>
                                <form action="{{ route('pimpinan.reject', $overtime->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" onclick="return confirm('Tolak lemburan ini?')" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-[10px] font-extrabold rounded-lg shadow-sm border border-red-500 transition-all active:scale-95">TOLAK</button>
                                </form>
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
