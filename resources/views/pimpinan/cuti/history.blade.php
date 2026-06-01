@extends('pimpinan.layouts.app')

@section('header_title', 'Riwayat Cuti Karyawan')

@section('content')
<div class="mb-8 px-4">
    <h1 class="text-3xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Riwayat Cuti Disetujui</h1>
    <p class="text-sm text-slate-500 mt-1 font-medium italic opacity-80">Arsip seluruh berkas pengajuan cuti karyawan Bagian {{ auth()->user()->bagian }} yang telah Anda setujui.</p>
</div>

<div class="px-4 mb-10">
    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 p-8 overflow-hidden">
        <div class="overflow-x-auto rounded-[2rem] border border-slate-100">
            <table class="w-full text-left border-collapse min-w-full">
                <thead>
                    <tr class="bg-slate-900 text-white text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4 rounded-tl-[2rem]">Tgl. Disetujui</th>
                        <th class="px-6 py-4">Karyawan</th>
                        <th class="px-6 py-4">Jadwal Cuti</th>
                        <th class="px-6 py-4 text-center">Durasi</th>
                        <th class="px-6 py-4">Alasan Cuti</th>
                        <th class="px-6 py-4 text-center rounded-tr-[2rem]">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                                {{ $leave->updated_at->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="block text-slate-850 font-bold text-sm">{{ $leave->employee_name }}</span>
                                <span class="text-xs text-slate-400 font-medium">NIK: {{ $leave->employee_no }} | Divisi: {{ $leave->divisi }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="block text-xs font-bold text-slate-800">
                                    {{ \Carbon\Carbon::parse($leave->tanggal_mulai)->translatedFormat('d M Y') }} s/d 
                                    {{ \Carbon\Carbon::parse($leave->tanggal_selesai)->translatedFormat('d M Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-700">
                                {{ $leave->total_hari }} Hari
                            </td>
                            <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="{{ $leave->alasan }}">
                                {{ $leave->alasan }}
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="px-3.5 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-extrabold rounded-xl border border-emerald-100/80 shadow-sm uppercase tracking-wider">Disetujui</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                                Belum ada berkas riwayat cuti disetujui untuk bagian Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        <div class="mt-6">
            {{ $leaves->links() }}
        </div>
    </div>
</div>
@endsection
