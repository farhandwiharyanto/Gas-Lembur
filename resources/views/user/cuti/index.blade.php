@extends('user.layouts.app')

@section('header_title', 'Riwayat Cuti Karyawan')

@section('content')
<div class="mb-8 px-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Riwayat Pengajuan Cuti</h1>
        <p class="text-sm text-slate-500 mt-1 font-medium italic opacity-80">Lihat histori dan status persetujuan berkas pengajuan cuti Anda.</p>
    </div>
    <a href="{{ route('user.cuti.create') }}" class="inline-flex items-center px-6 py-3.5 bg-slate-900 hover:bg-indigo-600 text-white font-bold rounded-2xl transition-all duration-300 shadow-lg uppercase tracking-wider text-xs font-outfit self-start sm:self-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Pengajuan
    </a>
</div>

<!-- Filters Panel -->
<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 mb-8 mx-4">
    <form action="{{ route('user.cuti.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex-1 space-y-1">
            <label for="month" class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Filter Berdasarkan Bulan</label>
            <input type="month" 
                   name="month" 
                   id="month" 
                   value="{{ $selectedMonth }}"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white rounded-xl transition-colors font-semibold text-slate-800 text-xs outline-none">
        </div>
        <div class="flex items-end self-stretch sm:self-end pt-5">
            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all duration-300 uppercase tracking-widest text-[10px]">
                Filter Data
            </button>
            @if($selectedMonth)
                <a href="{{ route('user.cuti.index') }}" class="ml-2 px-6 py-3 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-xl transition-all duration-300 uppercase tracking-widest text-[10px] text-center">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- History Table -->
<div class="px-4 mb-10">
    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 p-8 overflow-hidden">
        <div class="overflow-x-auto rounded-[2rem] border border-slate-100">
            <table class="w-full text-left border-collapse min-w-full">
                <thead>
                    <tr class="bg-slate-900 text-white text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4 rounded-tl-[2rem]">Tanggal Mulai</th>
                        <th class="px-6 py-4">Tanggal Selesai</th>
                        <th class="px-6 py-4 text-center">Durasi Kerja</th>
                        <th class="px-6 py-4">Alasan Cuti</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center rounded-tr-[2rem]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-slate-800 font-bold">
                                {{ \Carbon\Carbon::parse($leave->tanggal_mulai)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 text-slate-800">
                                {{ \Carbon\Carbon::parse($leave->tanggal_selesai)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-700">
                                {{ $leave->total_hari }} Hari
                            </td>
                            <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="{{ $leave->alasan }}">
                                {{ $leave->alasan }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($leave->status === 'approved')
                                    <span class="px-3.5 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-extrabold rounded-xl border border-emerald-100/80 shadow-sm uppercase tracking-wider">Disetujui</span>
                                @elseif($leave->status === 'rejected')
                                    <span class="px-3.5 py-1.5 bg-red-50 text-red-700 text-xs font-extrabold rounded-xl border border-red-100/80 shadow-sm uppercase tracking-wider">Ditolak</span>
                                @else
                                    <span class="px-3.5 py-1.5 bg-amber-50 text-amber-700 text-xs font-extrabold rounded-xl border border-amber-100/80 shadow-sm uppercase tracking-wider animate-pulse">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    @if($leave->status === 'pending')
                                        <a href="{{ route('user.cuti.edit', $leave->id) }}" class="p-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg transition-colors" title="Edit Pengajuan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        
                                        <form action="{{ route('user.cuti.destroy', $leave->id) }}" method="POST" id="deleteForm-{{ $leave->id }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    onclick="confirmDelete('{{ $leave->id }}', '{{ \Carbon\Carbon::parse($leave->tanggal_mulai)->translatedFormat('d M Y') }}')" 
                                                    class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors" 
                                                    title="Batalkan Pengajuan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <!-- Print/Download PDF Button -->
                                    <a href="{{ route('user.cuti.print', $leave->id) }}" target="_blank" class="p-2 bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-600 rounded-lg transition-colors border border-slate-200 hover:border-indigo-600" title="Unduh PDF Formulir Cuti">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                                Tidak ada data pengajuan cuti yang ditemukan.
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

@push('scripts')
<script>
function confirmDelete(id, dateStr) {
    showConfirm({
        title: 'Batalkan Pengajuan Cuti',
        message: `Apakah Anda yakin ingin menghapus/membatalkan berkas pengajuan cuti untuk tanggal <strong>${dateStr}</strong>?<br><br>Tindakan ini permanen dan tidak dapat dikembalikan.`,
        confirmText: 'Ya, Hapus',
        cancelText: 'Batal',
        type: 'danger',
        onConfirm: () => {
            const form = document.getElementById('deleteForm-' + id);
            if(form) {
                form.submit();
            }
        }
    });
}
</script>
@endpush
