@extends('pimpinan.layouts.app')

@section('header_title', 'Manajemen Lembur')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <form action="{{ route('pimpinan.bulk_approve') }}" method="POST" id="bulkApproveForm">
        @csrf
        @method('PATCH')
        <input type="hidden" name="all_selected" id="allSelectedInput" value="0">
        
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-slate-50/30">
            <div>
                <h2 class="text-xl font-extrabold text-gray-800 font-outfit uppercase tracking-tight">Menunggu Approval Pimpinan</h2>
                <p class="text-xs text-gray-400 mt-1 font-semibold uppercase tracking-wider">Pilih beberapa pengajuan lembur untuk menyetujuinya secara masal.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button type="button" id="bulkApproveBtn" disabled onclick="confirmBulkApprove(event)" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-extrabold rounded-xl transition-all shadow-md active:scale-95 uppercase tracking-widest disabled:opacity-30 disabled:cursor-not-allowed">
                    Bulk Approve
                </button>
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
                <p class="font-bold">Gagal</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Selection count banner (emerald themed) -->
        <div id="selectionBanner" class="hidden px-8 py-4 bg-emerald-50/80 border-b border-emerald-100 flex items-center justify-center text-xs transition-all duration-300">
            <span id="bannerText" class="text-emerald-900 font-bold uppercase tracking-tight"></span>
            <button type="button" id="selectAllAcross" class="ml-3 px-3 py-1 bg-white border border-emerald-200 text-emerald-600 font-black rounded-lg hover:bg-emerald-600 hover:text-white transition-all uppercase tracking-widest text-[10px]">Pilih semua data ({{ $overtimes->total() }})</button>
            <button type="button" id="clearSelection" class="hidden ml-3 px-3 py-1 bg-emerald-100 text-emerald-700 font-black rounded-lg hover:bg-emerald-200 transition-all uppercase tracking-widest text-[10px]">Batalkan</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold text-center w-12">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        </th>
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
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $overtime->id }}" class="row-checkbox w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            </td>
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
                                <span class="bg-yellow-100 text-yellow-800 px-2.5 py-1 rounded-full border border-yellow-200">
                                    MENUNGGU
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-bold uppercase tracking-widest">
                                <div class="flex justify-center flex-wrap items-center gap-2">
                                    <button type="button" onclick="singleApprove({{ $overtime->id }})" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-extrabold rounded-lg shadow-sm border border-emerald-500 transition-all active:scale-95">SETUJU</button>
                                    <button type="button" onclick="singleReject({{ $overtime->id }})" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-[10px] font-extrabold rounded-lg shadow-sm border border-red-500 transition-all active:scale-95">TOLAK</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-bold uppercase tracking-widest text-[10px]">
                                Belum ada pengajuan lembur yang menunggu approval Anda.
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
    </form>
</div>

<!-- Forms for single approvals/rejections to preserve old behavior securely -->
<form id="singleApproveForm" method="POST" class="hidden">
    @csrf @method('PATCH')
</form>
<form id="singleRejectForm" method="POST" class="hidden">
    @csrf @method('PATCH')
</form>
@endsection

@push('scripts')
<script>
    function singleApprove(id) {
        showConfirm({
            title: 'Setujui Lemburan',
            message: 'Apakah Anda yakin ingin menyetujui pengajuan lembur dari karyawan ini?',
            confirmText: 'Ya, Setujui',
            cancelText: 'Kembali',
            type: 'approve',
            onConfirm: () => {
                const form = document.getElementById('singleApproveForm');
                form.action = `/pimpinan/approve/${id}`;
                form.submit();
            }
        });
    }

    function singleReject(id) {
        showConfirm({
            title: 'Tolak Lemburan',
            message: 'Apakah Anda yakin ingin menolak pengajuan lembur ini?<br><span class="text-xs text-red-400 mt-1 block">Tindakan ini tidak dapat dibatalkan.</span>',
            confirmText: 'Ya, Tolak',
            cancelText: 'Kembali',
            type: 'reject',
            onConfirm: () => {
                const form = document.getElementById('singleRejectForm');
                form.action = `/pimpinan/reject/${id}`;
                form.submit();
            }
        });
    }

    function confirmBulkApprove(event) {
        event.preventDefault();
        
        const allSelectedInput = document.getElementById('allSelectedInput');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        const totalRecords = {{ $overtimes->total() }};
        
        let countText = '';
        if (allSelectedInput && allSelectedInput.value === '1') {
            countText = `<strong>seluruh ${totalRecords} data</strong>`;
        } else {
            countText = `<strong>${checkedCount} data</strong>`;
        }
        
        showConfirm({
            title: 'Bulk Approval',
            message: `Apakah Anda yakin ingin menyetujui ${countText} pengajuan lembur yang terpilih secara masal?`,
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
        
        const totalRecords = {{ $overtimes->total() }};
        const currentPageCount = checkboxes.length;

        function updateBtnState() {
            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            
            if (allSelectedInput.value === '1') {
                bulkApproveBtn.disabled = false;
                bulkApproveBtn.innerHTML = `Bulk Approve (${totalRecords})`;
            } else {
                bulkApproveBtn.disabled = checkedCount === 0;
                bulkApproveBtn.innerHTML = checkedCount > 0 ? `Bulk Approve (${checkedCount})` : 'Bulk Approve';
            }

            // Banner logic
            if (selectAll.checked && totalRecords > currentPageCount && allSelectedInput.value === '0') {
                selectionBanner.classList.remove('hidden');
                bannerText.innerText = `Semua ${currentPageCount} data di halaman ini terpilih.`;
                selectAllAcross.classList.remove('hidden');
                clearSelection.classList.add('hidden');
            } else if (allSelectedInput.value === '1') {
                selectionBanner.classList.remove('hidden');
                bannerText.innerText = `Seluruh ${totalRecords} data telah terpilih.`;
                selectAllAcross.classList.add('hidden');
                clearSelection.classList.remove('hidden');
            } else {
                selectionBanner.classList.add('hidden');
            }
        }

        if(selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
                if (!selectAll.checked) {
                    allSelectedInput.value = '0';
                }
                updateBtnState();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                selectAll.checked = allChecked;
                if (!this.checked) {
                    allSelectedInput.value = '0';
                }
                updateBtnState();
            });
        });

        if(selectAllAcross) {
            selectAllAcross.addEventListener('click', function() {
                allSelectedInput.value = '1';
                updateBtnState();
            });
        }

        if(clearSelection) {
            clearSelection.addEventListener('click', function() {
                allSelectedInput.value = '0';
                selectAll.checked = false;
                checkboxes.forEach(cb => cb.checked = false);
                updateBtnState();
            });
        }
    });
</script>
@endpush
