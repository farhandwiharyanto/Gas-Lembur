@extends('user.layouts.app')

@section('header_title', 'Riwayat Lembur')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-6xl mx-auto">
    <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Daftar Pengajuan Lembur Anda</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau status dari semua pengajuan lembur yang telah Anda buat.</p>
        </div>
        <a href="{{ route('overtime.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
            + Buat Pengajuan
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold">TGL. PENGISIAN FORMULIR</th>
                    <th scope="col" class="px-6 py-4 font-semibold">NAMA & NIK</th>
                    <th scope="col" class="px-6 py-4 font-semibold">BAGIAN</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-center">STATUS</th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                            {{ $overtime->bagian }}<br>
                            <span class="text-xs text-gray-400">{{ $overtime->divisi }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($overtime->status == 'waiting')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full border border-yellow-200">
                                    Menunggu Approval
                                </span>
                            @elseif($overtime->status == 'approved')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full border border-green-200">
                                    Disetujui
                                </span>
                            @elseif($overtime->status == 'rejected')
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-1 rounded-full border border-red-200">
                                    Ditolak
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-1 rounded-full border border-gray-200">
                                    {{ $overtime->status }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p>Belum ada riwayat pengajuan lembur.</p>
                            </div>
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
