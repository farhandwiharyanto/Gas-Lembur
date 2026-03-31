@extends('user.layouts.app')

@section('header_title', 'Riwayat Lembur')

@section('content')
<!-- Dashboard Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 max-w-6xl mx-auto">
    <div class="bg-green-600 rounded-[2rem] shadow-[0_10px_30px_rgba(22,163,74,0.25)] p-8 flex flex-col justify-center items-center text-white transform hover:scale-105 transition-transform duration-300">
        <div class="text-green-100 text-[10px] font-bold mb-2 uppercase tracking-widest opacity-80">Total Jam (Disetujui)</div>
        <div class="text-4xl font-extrabold font-outfit">{{ (int)$totalApproved }} <span class="text-sm font-medium opacity-60">JAM</span></div>
    </div>
    <div class="bg-yellow-500 rounded-[2rem] shadow-[0_10px_30px_rgba(234,179,8,0.2)] p-8 flex flex-col justify-center items-center text-white transform hover:scale-105 transition-transform duration-300">
        <div class="text-yellow-100 text-[10px] font-bold mb-2 uppercase tracking-widest opacity-80">Total Jam (Menunggu)</div>
        <div class="text-4xl font-extrabold font-outfit text-white">{{ (int)$totalWaiting }} <span class="text-sm font-medium opacity-60 text-white/70">JAM</span></div>
    </div>
    <div class="bg-red-600 rounded-[2rem] shadow-[0_10px_30px_rgba(220,38,38,0.2)] p-8 flex flex-col justify-center items-center text-white transform hover:scale-105 transition-transform duration-300">
        <div class="text-red-100 text-[10px] font-bold mb-2 uppercase tracking-widest opacity-80">Total Jam (Ditolak)</div>
        <div class="text-4xl font-extrabold font-outfit text-white">{{ (int)$totalRejected }} <span class="text-sm font-medium opacity-60 text-white/70">JAM</span></div>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden max-w-6xl mx-auto mb-10">
    <form action="{{ route('user.bulk_download') }}" method="POST" id="bulkDownloadForm">
        @csrf
        <div class="px-8 py-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Riwayat Pengajuan Lembur</h2>
                <p class="text-xs text-slate-400 mt-1 font-semibold uppercase tracking-wider">Pilih beberapa data untuk diunduh menjadi satu file PDF.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button type="submit" id="bulkBtn" disabled class="px-5 py-2.5 bg-slate-800 hover:bg-black text-white text-[11px] font-extrabold rounded-xl transition-all shadow-md active:scale-95 uppercase tracking-widest disabled:opacity-30 disabled:cursor-not-allowed">
                    Bulk Download (PDF)
                </button>
                <a href="{{ route('overtime.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-extrabold rounded-xl transition-all shadow-md active:scale-95 uppercase tracking-widest">
                    + Buat Baru
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="text-[10px] text-slate-400 uppercase bg-slate-100/50 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold text-center">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-widest">Tgl. Pengajuan</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-widest">Detail Pelaksanaan</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-widest text-center">Status</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($overtimes as $overtime)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                @if($overtime->status == 'approved')
                                    <input type="checkbox" name="ids[]" value="{{ $overtime->id }}" class="row-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                @else
                                    <span class="w-4 h-4 inline-block opacity-20">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700 font-bold">
                                {{ $overtime->created_at->format('d M Y') }}<br>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $overtime->created_at->format('H:i') }} WIB</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-[10px] text-slate-700 font-bold mt-1 uppercase tracking-tight">
                                    Mulai: {{ \Carbon\Carbon::parse($overtime->tanggal_masuk)->format('d M') }} ({{ $overtime->jam_masuk }})<br>
                                    Selesai: {{ \Carbon\Carbon::parse($overtime->tanggal_keluar)->format('d M') }} ({{ $overtime->jam_keluar }})
                                </div>
                                <div class="text-[10px] text-indigo-600 font-extrabold uppercase mt-1 tracking-tighter">
                                    Total: {{ round($overtime->total_jam) ?: '0' }} Jam
                                </div>
                                <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider mt-1 italic">
                                    Tiket: {{ $overtime->nomor_tiket ?: '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($overtime->status == 'waiting' || $overtime->status == 'pending')
                                    <span class="bg-yellow-50 text-yellow-600 text-[10px] font-extrabold px-3 py-1 rounded-full border border-yellow-100 uppercase tracking-widest">MENUNGGU</span>
                                @elseif($overtime->status == 'approved')
                                    <span class="bg-green-50 text-green-600 text-[10px] font-extrabold px-3 py-1 rounded-full border border-green-100 uppercase tracking-widest">DISETUJUI</span>
                                @elseif($overtime->status == 'rejected')
                                    <span class="bg-red-50 text-red-600 text-[10px] font-extrabold px-3 py-1 rounded-full border border-red-100 uppercase tracking-widest">DITOLAK</span>
                                @else
                                    <span class="bg-slate-50 text-slate-600 text-[10px] font-extrabold px-3 py-1 rounded-full border border-slate-100 uppercase tracking-widest">{{ strtoupper($overtime->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center space-x-1">
                                    @if($overtime->status == 'waiting' || $overtime->status == 'pending')
                                        <a href="{{ route('overtime.edit', $overtime->id) }}" class="p-2 bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white rounded-lg transition-all border border-yellow-100" title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('overtime.print', $overtime->id) }}" target="_blank" class="p-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-lg transition-all border border-indigo-100" title="Cetak Satuan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[11px]">Belum ada riwayat pengajuan lembur yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($overtimes->hasPages())
            <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/20">
                {{ $overtimes->links() }}
            </div>
        @endif
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const bulkBtn = document.getElementById('bulkBtn');

        function updateBtnState() {
            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            bulkBtn.disabled = checkedCount === 0;
            bulkBtn.innerHTML = checkedCount > 0 ? `Bulk Download (${checkedCount}) PDF` : 'Bulk Download (PDF)';
        }

        if(selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
                updateBtnState();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                selectAll.checked = allChecked;
                updateBtnState();
            });
        });
    });
</script>
@endpush
@endsection
