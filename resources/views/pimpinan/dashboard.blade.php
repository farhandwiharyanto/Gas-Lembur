@extends('pimpinan.layouts.app')

@section('header_title', 'Dashboard Pimpinan')

@section('content')
<div class="mb-8 px-4">
    <h1 class="text-3xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight font-outfit">Ringkasan Lembur & Cuti Karyawan</h1>
    <p class="text-sm text-slate-500 mt-1 font-medium italic opacity-80">Pantau statistik jam lembur dan cuti karyawan di departemen Anda yang telah disetujui (Approved).</p>
</div>

<!-- Dashboard Metric Cards (5 Columns Responsive) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-10 px-4">
    <!-- Lembur Approved (Emerald Green) -->
    <div class="bg-emerald-600 rounded-[2rem] shadow-[0_10px_30px_rgba(16,185,129,0.2)] p-6 flex flex-col justify-center items-center text-white transform hover:scale-105 transition-transform duration-300">
        <div class="text-emerald-100 text-[10px] font-bold mb-2 uppercase tracking-widest text-center opacity-85">Lembur Disetujui</div>
        <div class="text-3xl font-extrabold font-outfit">{{ (int)$totalApproved }} <span class="text-xs font-medium opacity-65">JAM</span></div>
    </div>
    
    <!-- Lembur Waiting (Amber Yellow) -->
    <div class="bg-amber-500 rounded-[2rem] shadow-[0_10px_30px_rgba(245,158,11,0.15)] p-6 flex flex-col justify-center items-center text-white transform hover:scale-105 transition-transform duration-300 border border-amber-400/30">
        <div class="text-amber-100 text-[10px] font-bold mb-2 uppercase tracking-widest text-center opacity-85">Lembur Menunggu</div>
        <div class="text-3xl font-extrabold font-outfit text-white">{{ (int)$totalWaiting }} <span class="text-xs font-medium opacity-65 text-white/70">JAM</span></div>
    </div>
    
    <!-- Lembur Rejected (Rose Red) -->
    <div class="bg-red-600 rounded-[2rem] shadow-[0_10px_30px_rgba(220,38,38,0.2)] p-6 flex flex-col justify-center items-center text-white transform hover:scale-105 transition-transform duration-300 border border-red-500/30">
        <div class="text-red-100 text-[10px] font-bold mb-2 uppercase tracking-widest text-center opacity-85">Lembur Ditolak</div>
        <div class="text-3xl font-extrabold font-outfit text-white">{{ (int)$totalRejected }} <span class="text-xs font-medium opacity-65 text-white/70">JAM</span></div>
    </div>

    <!-- Cuti Approved (Indigo Blue) -->
    <div class="bg-indigo-600 rounded-[2rem] shadow-[0_10px_30px_rgba(79,70,229,0.2)] p-6 flex flex-col justify-center items-center text-white transform hover:scale-105 transition-transform duration-300 border border-indigo-500/30">
        <div class="text-indigo-100 text-[10px] font-bold mb-2 uppercase tracking-widest text-center opacity-85">Cuti Disetujui</div>
        <div class="text-3xl font-extrabold font-outfit text-white">{{ (int)$totalCutiApproved }} <span class="text-xs font-medium opacity-65 text-indigo-200">HARI</span></div>
    </div>

    <!-- Cuti Pending (Rose Red) -->
    <div class="bg-rose-600 rounded-[2rem] shadow-[0_10px_30px_rgba(225,29,72,0.2)] p-6 flex flex-col justify-center items-center text-white transform hover:scale-105 transition-transform duration-300 border border-rose-500/30">
        <div class="text-rose-100 text-[10px] font-bold mb-2 uppercase tracking-widest text-center opacity-85">Cuti Menunggu</div>
        <div class="text-3xl font-extrabold font-outfit text-white">{{ (int)$totalCutiPending }} <span class="text-xs font-medium opacity-65 text-rose-200">HARI</span></div>
    </div>
</div>

<!-- SECTION 1: OVERTIME (LEMBUR) CHARTS & TABLES -->
<div class="mb-6 px-4">
    <h2 class="text-2xl font-black text-slate-800 font-outfit uppercase tracking-tight pb-2 border-b border-slate-200/50">I. Ringkasan Lembur Departemen</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10 px-4">
    <!-- Lembur Chart -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 flex flex-col justify-between overflow-hidden">
        <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-6">
            <h3 class="text-lg font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Grafik Lembur 10 Teratas</h3>
            <span class="px-3.5 py-1 bg-emerald-50 text-emerald-600 rounded-xl text-[8px] font-extrabold uppercase tracking-widest">Karyawan vs Jam</span>
        </div>
        
        @if(count($data) > 0)
            <div class="relative h-80 w-full mt-auto">
                <canvas id="overtimeChart"></canvas>
            </div>
        @else
            <div class="text-center py-16 text-slate-400 my-auto">
                <svg class="mx-auto h-12 w-12 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <p class="text-sm font-bold text-slate-500">Belum ada data lembur disetujui.</p>
            </div>
        @endif
    </div>

    <!-- Lembur Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 flex flex-col justify-between overflow-hidden">
        <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-6">
            <h3 class="text-lg font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Daftar Top 10 Karyawan</h3>
            <span class="px-3.5 py-1 bg-slate-50 text-slate-600 rounded-xl text-[8px] font-extrabold uppercase tracking-widest">Detail Jam</span>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-50">
            <table class="min-w-full divide-y divide-slate-100 text-left text-sm font-medium">
                <thead class="bg-slate-900 text-white text-[10px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-xl">Nama Karyawan</th>
                        <th class="px-4 py-3 text-right rounded-tr-xl">Total Jam Disetujui</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($top10 as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3.5 text-slate-800 font-bold">{{ $item->employee_name }}</td>
                        <td class="px-4 py-3.5 text-indigo-600 font-black text-right">{{ (int)$item->total_jam }} JAM</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-4 py-12 text-center text-slate-400 italic">Belum ada data tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SECTION 2: LEAVE (CUTI) CHARTS & TABLES -->
<div class="mb-6 px-4">
    <h2 class="text-2xl font-black text-slate-800 font-outfit uppercase tracking-tight pb-2 border-b border-slate-200/50">II. Ringkasan Cuti Departemen</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10 px-4">
    <!-- Cuti Chart -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 flex flex-col justify-between overflow-hidden">
        <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-6">
            <h3 class="text-lg font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Grafik Cuti 10 Teratas</h3>
            <span class="px-3.5 py-1 bg-indigo-50 text-indigo-600 rounded-xl text-[8px] font-extrabold uppercase tracking-widest">Karyawan vs Hari</span>
        </div>
        
        @if(count($cutiData) > 0)
            <div class="relative h-80 w-full mt-auto">
                <canvas id="cutiChart"></canvas>
            </div>
        @else
            <div class="text-center py-16 text-slate-400 my-auto">
                <svg class="mx-auto h-12 w-12 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-sm font-bold text-slate-500">Belum ada data cuti disetujui.</p>
            </div>
        @endif
    </div>

    <!-- Cuti Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 flex flex-col justify-between overflow-hidden">
        <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-6">
            <h3 class="text-lg font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Daftar Top 10 Karyawan Cuti</h3>
            <span class="px-3.5 py-1 bg-slate-50 text-slate-600 rounded-xl text-[8px] font-extrabold uppercase tracking-widest">Detail Hari</span>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-50">
            <table class="min-w-full divide-y divide-slate-100 text-left text-sm font-medium">
                <thead class="bg-slate-900 text-white text-[10px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-xl">Nama Karyawan</th>
                        <th class="px-4 py-3 text-right rounded-tr-xl">Total Hari Cuti Disetujui</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($top10Cuti as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3.5 text-slate-800 font-bold">{{ $item->employee_name }}</td>
                        <td class="px-4 py-3.5 text-indigo-600 font-black text-right">{{ (int)$item->total_hari }} HARI</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-4 py-12 text-center text-slate-400 italic">Belum ada data tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Overtime Chart
        @if(count($data) > 0)
        const ctxOvertime = document.getElementById('overtimeChart');
        if(ctxOvertime) {
            new Chart(ctxOvertime, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        label: 'Total Jam Lembur Disetujui',
                        data: {!! json_encode($data) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.75)', // Emerald Green
                        borderColor: 'rgba(16, 185, 129, 1)',      
                        borderWidth: 1.5,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, family: 'Outfit', weight: '700' },
                            bodyFont: { size: 11, family: 'Inter' },
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Jam Disetujui';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { family: 'Inter', size: 10 } }
                        },
                        x: {
                            ticks: { font: { family: 'Outfit', size: 11, weight: '600' } }
                        }
                    }
                }
            });
        }
        @endif

        // 2. Cuti Chart
        @if(count($cutiData) > 0)
        const ctxCuti = document.getElementById('cutiChart');
        if(ctxCuti) {
            new Chart(ctxCuti, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($cutiLabels) !!},
                    datasets: [{
                        label: 'Total Hari Cuti Disetujui',
                        data: {!! json_encode($cutiData) !!},
                        backgroundColor: 'rgba(79, 70, 229, 0.75)', // Indigo Blue
                        borderColor: 'rgba(79, 70, 229, 1)',      
                        borderWidth: 1.5,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, family: 'Outfit', weight: '700' },
                            bodyFont: { size: 11, family: 'Inter' },
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Hari Cuti';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { family: 'Inter', size: 10 } }
                        },
                        x: {
                            ticks: { font: { family: 'Outfit', size: 11, weight: '600' } }
                        }
                    }
                }
            });
        }
        @endif
    });
</script>
@endpush
