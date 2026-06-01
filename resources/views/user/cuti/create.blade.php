@extends('user.layouts.app')

@section('header_title', isset($leave) ? 'Edit Pengajuan Cuti' : 'Buat Pengajuan Cuti')

@section('content')
<div class="mb-8 px-4">
    <h1 class="text-3xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">
        {{ isset($leave) ? 'Ubah Pengajuan Cuti' : 'Form Input Cuti' }}
    </h1>
    <p class="text-sm text-slate-500 mt-1 font-medium italic opacity-80">
        {{ isset($leave) ? 'Perbarui berkas detail pengajuan cuti Anda di bawah ini.' : 'Silahkan isi form di bawah ini untuk mengajukan cuti baru.' }}
    </p>
</div>

<div class="max-w-3xl mx-auto px-4 mb-10">
    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 p-8 sm:p-10 relative overflow-hidden">
        <!-- Glow accents -->
        <div class="absolute -right-24 -top-24 w-52 h-52 rounded-full bg-indigo-500/5 blur-3xl"></div>
        
        <form action="{{ isset($leave) ? route('user.cuti.update', $leave->id) : route('user.cuti.store') }}" 
              method="POST" 
              id="leaveForm" 
              onsubmit="confirmLeaveSubmit(event)"
              class="space-y-6 relative z-10">
            @csrf
            @if(isset($leave))
                @method('PUT')
            @endif

            <!-- Dates Selection Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tanggal Mulai -->
                <div class="space-y-2">
                    <label for="tanggal_mulai" class="block text-xs font-black uppercase text-slate-500 tracking-wider">Tanggal Mulai Cuti</label>
                    <input type="date" 
                           name="tanggal_mulai" 
                           id="tanggal_mulai" 
                           required 
                           min="{{ isset($leave) ? '' : date('Y-m-d', strtotime('-1 year')) }}"
                           value="{{ old('tanggal_mulai', isset($leave) ? $leave->tanggal_mulai : '') }}"
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white rounded-2xl transition-all duration-300 font-semibold text-slate-800 text-sm outline-none shadow-sm focus:ring-4 focus:ring-indigo-100">
                    @error('tanggal_mulai')
                        <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Selesai -->
                <div class="space-y-2">
                    <label for="tanggal_selesai" class="block text-xs font-black uppercase text-slate-500 tracking-wider">Tanggal Selesai Cuti</label>
                    <input type="date" 
                           name="tanggal_selesai" 
                           id="tanggal_selesai" 
                           required 
                           value="{{ old('tanggal_selesai', isset($leave) ? $leave->tanggal_selesai : '') }}"
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white rounded-2xl transition-all duration-300 font-semibold text-slate-800 text-sm outline-none shadow-sm focus:ring-4 focus:ring-indigo-100">
                    @error('tanggal_selesai')
                        <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Real-time Working Days Count Box -->
            <div id="daysCounterBox" class="bg-indigo-50/50 border border-indigo-100/50 p-6 rounded-2xl flex items-center justify-between transition-all duration-500 opacity-60">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-indigo-600 text-white rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-extrabold text-indigo-950 font-outfit uppercase tracking-tight">Kalkulasi Hari Kerja</h4>
                        <p class="text-xs text-indigo-500 font-medium">Mengecualikan hari Sabtu dan Minggu otomatis.</p>
                    </div>
                </div>
                <div class="text-right">
                    <span id="calculatedDays" class="text-3xl font-black text-indigo-600 font-outfit">0</span>
                    <span class="text-xs font-bold text-indigo-500 uppercase tracking-widest block -mt-1">Hari Kerja</span>
                </div>
            </div>

            <!-- Alasan Cuti -->
            <div class="space-y-2">
                <label for="alasan" class="block text-xs font-black uppercase text-slate-500 tracking-wider">Alasan Pengajuan Cuti</label>
                <textarea name="alasan" 
                          id="alasan" 
                          rows="4" 
                          required 
                          placeholder="Jelaskan alasan cuti secara rinci..."
                          class="w-full px-5 py-4 bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white rounded-2xl transition-all duration-300 font-semibold text-slate-800 text-sm outline-none shadow-sm focus:ring-4 focus:ring-indigo-100 resize-none">{{ old('alasan', isset($leave) ? $leave->alasan : '') }}</textarea>
                @error('alasan')
                    <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Panel -->
            <div class="pt-6 flex items-center justify-between border-t border-slate-100">
                <a href="{{ route('user.cuti.index') }}" class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-2xl transition-all duration-300 uppercase tracking-widest text-xs">
                    Kembali
                </a>
                <button type="submit" 
                        class="px-10 py-4 bg-slate-900 hover:bg-indigo-600 text-white font-bold rounded-2xl shadow-[0_10px_20px_rgba(15,23,42,0.2)] hover:shadow-[0_15px_30px_rgba(79,70,229,0.3)] transition-all transform hover:-translate-y-0.5 active:scale-95 uppercase tracking-widest text-xs font-outfit">
                    {{ isset($leave) ? 'Simpan Perubahan' : 'Kirim Pengajuan' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    const calculatedDays = document.getElementById('calculatedDays');
    const daysCounterBox = document.getElementById('daysCounterBox');

    function calculateWorkingDays() {
        if (tanggalMulai.value && tanggalSelesai.value) {
            let start = new Date(tanggalMulai.value);
            let end = new Date(tanggalSelesai.value);
            
            if (end >= start) {
                let count = 0;
                let curDate = new Date(start.getTime());
                
                while (curDate <= end) {
                    let dayOfWeek = curDate.getDay();
                    if (dayOfWeek !== 0 && dayOfWeek !== 6) { // Exclude Sunday (0) and Saturday (6)
                        count++;
                    }
                    curDate.setDate(curDate.getDate() + 1);
                }
                
                calculatedDays.textContent = count;
                daysCounterBox.classList.remove('opacity-60', 'bg-indigo-50/50', 'border-indigo-100/50');
                daysCounterBox.classList.add('bg-indigo-50', 'border-indigo-200', 'shadow-sm');
            } else {
                calculatedDays.textContent = '0';
                daysCounterBox.classList.add('opacity-60');
            }
        } else {
            calculatedDays.textContent = '0';
            daysCounterBox.classList.add('opacity-60');
        }
    }

    tanggalMulai.addEventListener('change', function() {
        if (!tanggalSelesai.value || tanggalSelesai.value < tanggalMulai.value) {
            tanggalSelesai.value = tanggalMulai.value;
        }
        calculateWorkingDays();
    });

    tanggalSelesai.addEventListener('change', calculateWorkingDays);

    // Initial calculation on load (for edit views)
    calculateWorkingDays();
});

function confirmLeaveSubmit(event) {
    if (event.target.dataset.confirmed === 'true') {
        return;
    }
    
    event.preventDefault();
    
    const tanggalMulai = document.getElementById('tanggal_mulai')?.value || '';
    const tanggalSelesai = document.getElementById('tanggal_selesai')?.value || '';
    const totalHari = document.getElementById('calculatedDays')?.textContent || '0';
    const alasan = document.getElementById('alasan')?.value || '';
    
    if (Number(totalHari) === 0) {
        showConfirm({
            title: 'Pengajuan Gagal',
            message: 'Jumlah hari kerja cuti yang dihitung adalah 0. Silakan periksa kembali tanggal mulai dan selesai Anda.',
            confirmText: 'Mengerti',
            cancelText: 'Batal',
            type: 'danger'
        });
        return;
    }
    
    showConfirm({
        title: 'Kirim Pengajuan Cuti',
        message: `Apakah Anda yakin ingin mengirim berkas pengajuan cuti ini?<br><br>
                  <div class="bg-slate-950/40 p-5 rounded-[1.5rem] text-left border border-slate-800 text-xs space-y-2 font-sans text-slate-300">
                    <div><span class="text-slate-500 uppercase font-extrabold tracking-wider">Tanggal Mulai:</span> <span class="text-white font-bold">${tanggalMulai}</span></div>
                    <div><span class="text-slate-500 uppercase font-extrabold tracking-wider">Tanggal Selesai:</span> <span class="text-white font-bold">${tanggalSelesai}</span></div>
                    <div><span class="text-slate-500 uppercase font-extrabold tracking-wider">Durasi Kerja:</span> <span class="text-emerald-400 font-black">${totalHari} Hari Kerja</span></div>
                    <div class="pt-1.5 border-t border-slate-800/80"><span class="text-slate-500 uppercase font-extrabold tracking-wider block mb-0.5">Alasan:</span> <span class="text-slate-200 italic font-medium block">${alasan}</span></div>
                  </div>`,
        confirmText: 'Ya, Kirim',
        cancelText: 'Periksa Kembali',
        type: 'info',
        onConfirm: () => {
            const form = document.getElementById('leaveForm');
            form.dataset.confirmed = 'true';
            form.submit();
        }
    });
}
</script>
@endpush
