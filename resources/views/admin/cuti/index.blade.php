@extends('admin.layouts.app')

@section('header_title', 'Data Cuti Karyawan')

@section('content')
<div class="mb-8 px-4">
    <h1 class="text-3xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Master Data Cuti</h1>
    <p class="text-sm text-slate-500 mt-1 font-medium italic opacity-80">Kelola jatah cuti karyawan, lakukan override persetujuan, atau hapus berkas pengajuan.</p>
</div>

<div class="px-4 mb-10">
    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 p-8 overflow-hidden">
        <div class="overflow-x-auto rounded-[2rem] border border-slate-100">
            <table class="w-full text-left border-collapse min-w-full">
                <thead>
                    <tr class="bg-slate-900 text-white text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4 rounded-tl-[2rem]">Tgl. Pengajuan</th>
                        <th class="px-6 py-4">Karyawan</th>
                        <th class="px-6 py-4">Bagian & Lokasi</th>
                        <th class="px-6 py-4">Jadwal Cuti</th>
                        <th class="px-6 py-4">Alasan Cuti</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center rounded-tr-[2rem] w-48">Aksi Administratif</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                                {{ $leave->created_at->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="block text-slate-850 font-bold text-sm">{{ $leave->employee_name }}</span>
                                <span class="text-xs text-slate-400 font-medium">NIK: {{ $leave->employee_no }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="block text-slate-800 text-xs font-bold">{{ $leave->bagian }}</span>
                                <span class="text-xs text-slate-400 font-medium">{{ $leave->lokasi_kerja ?: 'Kantor Pusat' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-bold text-slate-800">
                                    {{ \Carbon\Carbon::parse($leave->tanggal_mulai)->translatedFormat('d M Y') }} s/d 
                                    {{ \Carbon\Carbon::parse($leave->tanggal_selesai)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-[10px] text-indigo-600 font-extrabold uppercase mt-1 tracking-wider">Total: {{ $leave->total_hari }} Hari Kerja</div>
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
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-1.5">
                                    @if($leave->status === 'pending')
                                        <!-- Normal Approve -->
                                        <button type="button" 
                                                onclick="adminAction('approve', {{ $leave->id }}, '{{ $leave->employee_name }}')"
                                                class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-[9px] font-black rounded-lg uppercase tracking-wider active:scale-95 shadow-sm"
                                                title="Setujui Cuti">
                                            Approve
                                        </button>
                                        <!-- Normal Reject -->
                                        <button type="button" 
                                                onclick="adminAction('reject', {{ $leave->id }}, '{{ $leave->employee_name }}')"
                                                class="px-2.5 py-1.5 bg-rose-600 hover:bg-rose-500 text-white text-[9px] font-black rounded-lg uppercase tracking-wider active:scale-95 shadow-sm"
                                                title="Tolak Cuti">
                                            Reject
                                        </button>
                                    @else
                                        <!-- Bypass / Force Approve Override (For rejected/completed states) -->
                                        <button type="button" 
                                                onclick="adminAction('force', {{ $leave->id }}, '{{ $leave->employee_name }}')"
                                                class="px-2.5 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-[9px] font-black rounded-lg uppercase tracking-wider active:scale-95 shadow-sm"
                                                title="Bypass Persetujuan">
                                            Bypass
                                        </button>
                                    @endif
                                    
                                    <!-- Delete from database -->
                                    <button type="button" 
                                            onclick="adminAction('delete', {{ $leave->id }}, '{{ $leave->employee_name }}')"
                                            class="p-1.5 bg-slate-100 hover:bg-red-50 text-slate-600 hover:text-red-600 rounded-lg transition-colors border border-slate-200 hover:border-red-200"
                                            title="Hapus Pengajuan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 italic">
                                Belum ada berkas data pengajuan cuti yang tercatat di sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Forms for admin actions -->
<form id="adminApproveForm" method="POST" class="hidden">
    @csrf @method('PATCH')
</form>
<form id="adminRejectForm" method="POST" class="hidden">
    @csrf @method('PATCH')
</form>
<form id="adminForceForm" method="POST" class="hidden">
    @csrf @method('PATCH')
</form>
<form id="adminDeleteForm" method="POST" class="hidden">
    @csrf @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    function adminAction(type, id, employeeName) {
        if (type === 'approve') {
            showConfirm({
                title: 'Setujui Cuti (Admin)',
                message: `Apakah Anda yakin ingin menyetujui pengajuan cuti karyawan <strong>${employeeName}</strong>?`,
                confirmText: 'Ya, Setujui',
                cancelText: 'Kembali',
                type: 'approve',
                onConfirm: () => {
                    const form = document.getElementById('adminApproveForm');
                    form.action = `/admin/cuti/approve/${id}`;
                    form.submit();
                }
            });
        } else if (type === 'reject') {
            showConfirm({
                title: 'Tolak Cuti (Admin)',
                message: `Apakah Anda yakin ingin menolak pengajuan cuti karyawan <strong>${employeeName}</strong>?`,
                confirmText: 'Ya, Tolak',
                cancelText: 'Kembali',
                type: 'reject',
                onConfirm: () => {
                    const form = document.getElementById('adminRejectForm');
                    form.action = `/admin/cuti/reject/${id}`;
                    form.submit();
                }
            });
        } else if (type === 'force') {
            showConfirm({
                title: 'Bypass / Force Approve Cuti',
                message: `Apakah Anda yakin ingin memaksa persetujuan (Force Approve) pengajuan cuti karyawan <strong>${employeeName}</strong>?<br><span class="text-xs text-blue-400 mt-1 block">Tindakan override ini akan langsung mengubah status menjadi Disetujui.</span>`,
                confirmText: 'Ya, Override',
                cancelText: 'Kembali',
                type: 'info',
                onConfirm: () => {
                    const form = document.getElementById('adminForceForm');
                    form.action = `/admin/cuti/force-approve/${id}`;
                    form.submit();
                }
            });
        } else if (type === 'delete') {
            showConfirm({
                title: 'Hapus Data Cuti Karyawan',
                message: `Apakah Anda yakin ingin menghapus berkas pengajuan cuti milik <strong>${employeeName}</strong>?<br><span class="text-xs text-red-400 mt-1 block">PERINGATAN: Berkas pengajuan akan dihapus permanen dari sistem database.</span>`,
                confirmText: 'Ya, Hapus Permanen',
                cancelText: 'Batal',
                type: 'danger',
                onConfirm: () => {
                    const form = document.getElementById('adminDeleteForm');
                    form.action = `/admin/cuti/${id}`;
                    form.submit();
                }
            });
        }
    }
</script>
@endpush
