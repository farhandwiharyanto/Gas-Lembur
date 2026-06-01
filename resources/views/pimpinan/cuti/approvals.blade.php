@extends('pimpinan.layouts.app')

@section('header_title', 'Persetujuan Cuti Karyawan')

@section('content')
<div class="mb-8 px-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Persetujuan Cuti</h1>
        <p class="text-sm text-slate-500 mt-1 font-medium italic opacity-80">Daftar pengajuan cuti karyawan di Bagian {{ auth()->user()->bagian }} yang memerlukan tindakan Anda.</p>
    </div>
</div>

<div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden mx-4 mb-10">
    <form action="{{ route('pimpinan.cuti.bulk_approve') }}" method="POST" id="bulkApproveForm">
        @csrf
        @method('PATCH')
        <input type="hidden" name="all_selected" id="allSelectedInput" value="0">
        
        <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Menunggu Approval</h2>
                <p class="text-xs text-slate-400 mt-1 font-semibold uppercase tracking-wider">Pilih beberapa pengajuan cuti untuk disetujui secara massal.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button type="button" 
                        id="bulkApproveBtn" 
                        disabled 
                        onclick="confirmBulkApprove(event)" 
                        class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-30 disabled:cursor-not-allowed text-white text-xs font-black rounded-xl transition-all shadow-md active:scale-95 uppercase tracking-widest">
                    Bulk Approve
                </button>
            </div>
        </div>

        <!-- Selection count banner (emerald themed) -->
        <div id="selectionBanner" class="hidden px-8 py-4 bg-emerald-50/80 border-b border-emerald-100 flex items-center justify-center text-xs transition-all duration-300">
            <span id="bannerText" class="text-emerald-900 font-bold uppercase tracking-tight"></span>
            @if($leaves->total() > $leaves->count())
                <button type="button" id="selectAllAcross" class="ml-3 px-3 py-1 bg-white border border-emerald-200 text-emerald-600 font-black rounded-lg hover:bg-emerald-600 hover:text-white transition-all uppercase tracking-widest text-[10px]">Pilih semua data ({{ $leaves->total() }})</button>
            @endif
            <button type="button" id="clearSelection" class="hidden ml-3 px-3 py-1 bg-emerald-100 text-emerald-700 font-black rounded-lg hover:bg-emerald-200 transition-all uppercase tracking-widest text-[10px]">Batalkan</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-full">
                <thead>
                    <tr class="bg-slate-900 text-white text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4 text-center w-12">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-slate-750 text-emerald-600 focus:ring-emerald-500 bg-slate-800">
                        </th>
                        <th class="px-6 py-4">Tgl. Pengajuan</th>
                        <th class="px-6 py-4">Karyawan</th>
                        <th class="px-6 py-4">Detail Pengajuan</th>
                        <th class="px-6 py-4">Alasan Cuti</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $leave->id }}" class="row-checkbox w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            </td>
                            <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                                {{ $leave->created_at->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="block text-slate-850 font-bold text-sm">{{ $leave->employee_name }}</span>
                                <span class="text-xs text-slate-400 font-medium">NIK: {{ $leave->employee_no }} | Divisi: {{ $leave->divisi }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-bold text-slate-800">
                                    {{ \Carbon\Carbon::parse($leave->tanggal_mulai)->translatedFormat('d M Y') }} s/d 
                                    {{ \Carbon\Carbon::parse($leave->tanggal_selesai)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-[11px] text-indigo-600 font-extrabold uppercase mt-1 tracking-wider">Durasi: {{ $leave->total_hari }} Hari Kerja</div>
                            </td>
                            <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="{{ $leave->alasan }}">
                                {{ $leave->alasan }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-amber-50 text-amber-700 text-xs font-extrabold rounded-lg border border-amber-100/80 uppercase tracking-widest animate-pulse">PENDING</span>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex justify-center items-center space-x-2">
                                    <button type="button" 
                                            onclick="singleApprove({{ $leave->id }}, '{{ $leave->employee_name }}')" 
                                            class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-black rounded-lg transition-colors shadow-sm uppercase tracking-widest active:scale-95">
                                        Setuju
                                    </button>
                                    <button type="button" 
                                            onclick="singleReject({{ $leave->id }}, '{{ $leave->employee_name }}')" 
                                            class="px-3.5 py-2 bg-red-600 hover:bg-red-500 text-white text-[10px] font-black rounded-lg transition-colors shadow-sm uppercase tracking-widest active:scale-95">
                                        Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 italic">
                                Tidak ada pengajuan cuti pending yang menunggu persetujuan Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leaves->hasPages())
            <div class="px-8 py-4 border-t border-slate-100">
                {{ $leaves->links() }}
            </div>
        @endif
    </form>
</div>

<!-- Forms for single approvals/rejections -->
<form id="singleApproveForm" method="POST" class="hidden">
    @csrf 
    @method('PATCH')
</form>
<form id="singleRejectForm" method="POST" class="hidden">
    @csrf 
    @method('PATCH')
</form>
@endsection

@push('scripts')
<script>
    function singleApprove(id, name) {
        showConfirm({
            title: 'Setujui Pengajuan Cuti',
            message: `Apakah Anda yakin ingin menyetujui permohonan cuti dari karyawan <strong>${name}</strong>?`,
            confirmText: 'Ya, Setujui',
            cancelText: 'Kembali',
            type: 'approve',
            onConfirm: () => {
                const form = document.getElementById('singleApproveForm');
                form.action = `/pimpinan/cuti/approve/${id}`;
                form.submit();
            }
        });
    }

    function singleReject(id, name) {
        showConfirm({
            title: 'Tolak Pengajuan Cuti',
            message: `Apakah Anda yakin ingin menolak permohonan cuti dari karyawan <strong>${name}</strong>?<br><span class="text-xs text-red-400 mt-1 block">Tindakan ini tidak dapat dibatalkan.</span>`,
            confirmText: 'Ya, Tolak',
            cancelText: 'Kembali',
            type: 'reject',
            onConfirm: () => {
                const form = document.getElementById('singleRejectForm');
                form.action = `/pimpinan/cuti/reject/${id}`;
                form.submit();
            }
        });
    }

    function confirmBulkApprove(event) {
        event.preventDefault();
        
        const allSelectedInput = document.getElementById('allSelectedInput');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        const totalRecords = {{ $leaves->total() }};
        
        let countText = '';
        if (allSelectedInput && allSelectedInput.value === '1') {
            countText = `<strong>seluruh ${totalRecords} data</strong>`;
        } else {
            countText = `<strong>${checkedCount} data</strong>`;
        }
        
        showConfirm({
            title: 'Bulk Approval Cuti',
            message: `Apakah Anda yakin ingin menyetujui ${countText} pengajuan cuti yang terpilih secara massal?`,
            confirmText: 'Ya, Setujui Semua',
            cancelText: 'Batal',
            type: 'approve',
            onConfirm: () => {
                document.getElementById('bulkApproveForm').submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const bulkApproveBtn = document.getElementById('bulkApproveBtn');
        const selectionBanner = document.getElementById('selectionBanner');
        const bannerText = document.getElementById('bannerText');
        const selectAllAcross = document.getElementById('selectAllAcross');
        const clearSelection = document.getElementById('clearSelection');
        const allSelectedInput = document.getElementById('allSelectedInput');
        
        const totalRecords = {{ $leaves->total() }};
        const currentPageCount = checkboxes.length;

        function updateBtnState() {
            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            
            if (allSelectedInput && allSelectedInput.value === '1') {
                bulkApproveBtn.disabled = false;
                bulkApproveBtn.innerHTML = `Bulk Approve (${totalRecords})`;
            } else {
                bulkApproveBtn.disabled = checkedCount === 0;
                bulkApproveBtn.innerHTML = checkedCount > 0 ? `Bulk Approve (${checkedCount})` : 'Bulk Approve';
            }

            // Banner logic
            if (selectAll && selectAll.checked && totalRecords > currentPageCount && allSelectedInput && allSelectedInput.value === '0') {
                selectionBanner.classList.remove('hidden');
                bannerText.innerText = `Semua ${currentPageCount} data di halaman ini terpilih.`;
                if(selectAllAcross) selectAllAcross.classList.remove('hidden');
                if(clearSelection) clearSelection.classList.add('hidden');
            } else if (allSelectedInput && allSelectedInput.value === '1') {
                selectionBanner.classList.remove('hidden');
                bannerText.innerText = `Seluruh ${totalRecords} data telah terpilih.`;
                if(selectAllAcross) selectAllAcross.classList.add('hidden');
                if(clearSelection) clearSelection.classList.remove('hidden');
            } else {
                selectionBanner.classList.add('hidden');
            }
        }

        if(selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
                if (!selectAll.checked && allSelectedInput) {
                    allSelectedInput.value = '0';
                }
                updateBtnState();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                if(selectAll) selectAll.checked = allChecked;
                if (!this.checked && allSelectedInput) {
                    allSelectedInput.value = '0';
                }
                updateBtnState();
            });
        });

        if(selectAllAcross) {
            selectAllAcross.addEventListener('click', function() {
                if(allSelectedInput) allSelectedInput.value = '1';
                updateBtnState();
            });
        }

        if(clearSelection) {
            clearSelection.addEventListener('click', function() {
                if(allSelectedInput) allSelectedInput.value = '0';
                if(selectAll) selectAll.checked = false;
                checkboxes.forEach(cb => {
                    cb.checked = false;
                });
                updateBtnState();
            });
        }
    });
</script>
@endpush
