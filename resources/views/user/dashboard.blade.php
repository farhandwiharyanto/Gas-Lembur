@extends('user.layouts.app')

@section('header_title', 'Dashboard Karyawan')

@section('content')
<div class="mb-8 px-4">
    <h1 class="text-3xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Dashboard Ringkasan</h1>
    <p class="text-sm text-slate-500 mt-1 font-medium italic opacity-80">Selamat datang kembali! Berikut adalah ringkasan kumulatif jam lembur dan sisa cuti Anda.</p>
</div>

<!-- Dashboard Metric Cards (4 Columns Responsive) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10 px-4">
    <!-- Lembur Approved (Emerald Green) -->
    <div class="bg-emerald-600 rounded-[2.5rem] shadow-[0_15px_40px_rgba(16,185,129,0.2)] p-8 flex flex-col justify-center items-center text-white transform hover:scale-[1.03] transition-all duration-500 border border-emerald-500/30">
        <div class="text-emerald-100 text-xs font-bold mb-3 uppercase tracking-[0.2em] opacity-80 text-center">Total Lembur (Disetujui)</div>
        <div class="text-4xl font-extrabold font-outfit tracking-tight">{{ (int)$totalApproved }} <span class="text-sm font-medium opacity-70 ml-1">JAM</span></div>
    </div>
    
    <!-- Lembur Waiting (Amber Yellow) -->
    <div class="bg-amber-500 rounded-[2.5rem] shadow-[0_15px_40px_rgba(245,158,11,0.15)] p-8 flex flex-col justify-center items-center text-white transform hover:scale-[1.03] transition-all duration-500 border border-amber-400/30">
        <div class="text-amber-100 text-xs font-bold mb-3 uppercase tracking-[0.2em] opacity-80 text-center">Lembur Menunggu</div>
        <div class="text-4xl font-extrabold font-outfit tracking-tight text-white">{{ (int)$totalWaiting }} <span class="text-sm font-medium opacity-70 ml-1 text-white/70">JAM</span></div>
    </div>

    <!-- Cuti Active Remaining (Indigo Blue) -->
    <div class="bg-indigo-600 rounded-[2.5rem] shadow-[0_15px_40px_rgba(79,70,229,0.2)] p-8 flex flex-col justify-center items-center text-white transform hover:scale-[1.03] transition-all duration-500 border border-indigo-500/30">
        <div class="text-indigo-100 text-xs font-bold mb-3 uppercase tracking-[0.2em] opacity-80 text-center">Sisa Cuti Aktif</div>
        <div class="text-4xl font-extrabold font-outfit tracking-tight text-white">{{ (int)$sisaCuti }} <span class="text-sm font-medium opacity-70 ml-1 text-indigo-200">HARI</span></div>
    </div>

    <!-- Cuti Used (Rose Red) -->
    <div class="bg-rose-600 rounded-[2.5rem] shadow-[0_15px_40px_rgba(225,29,72,0.2)] p-8 flex flex-col justify-center items-center text-white transform hover:scale-[1.03] transition-all duration-500 border border-rose-500/30">
        <div class="text-rose-100 text-xs font-bold mb-3 uppercase tracking-[0.2em] opacity-80 text-center">Cuti Terpakai</div>
        <div class="text-4xl font-extrabold font-outfit tracking-tight text-white">{{ (int)$usedLeave }} <span class="text-sm font-medium opacity-70 ml-1 text-rose-200">HARI</span></div>
    </div>
</div>

<!-- Two-Column Chart Layout -->
<div class="grid grid-cols-1 lg:grid-cols-5 gap-8 px-4 mb-10">
    <!-- Overtime Chart Column (Left, 3/5 width on lg) -->
    <div class="lg:col-span-3 bg-white rounded-[3rem] shadow-sm border border-slate-100 p-8 flex flex-col justify-between overflow-hidden">
        <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-6">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Grafik Intensitas Lembur</h2>
                <p class="text-xs text-slate-400 font-medium mt-1">Akumulasi jam lembur yang telah disetujui dalam 6 bulan terakhir.</p>
            </div>
            <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-xl text-[9px] font-extrabold uppercase tracking-widest border border-indigo-100/50 shadow-sm">Bulan vs Jam</span>
        </div>
        
        @if(count($data) > 0)
            <!-- Overtime Line Chart -->
            <div class="relative h-72 w-full">
                <canvas id="userOvertimeChart"></canvas>
            </div>
        @else
            <div class="text-center py-16 text-slate-400 bg-slate-50/50 rounded-[2rem] border-2 border-dashed border-slate-100">
                <svg class="mx-auto h-12 w-12 text-slate-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 3a1 1 0 100 2h2a1 1 0 100-2h-2zM4.5 8.25a.75.75 0 000 1.5h15a.75.75 0 000-1.5H4.5zM3 12a1 1 0 011-1h16a1 1 0 011 1v6.75A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75V12z" />
                </svg>
                <p class="text-sm font-bold text-slate-500">Belum Ada Data Statistik Lembur</p>
                <p class="text-xs max-w-xs mx-auto mt-1">Data grafik akan muncul setelah pengajuan lembur Anda disetujui.</p>
            </div>
        @endif
    </div>

    <!-- Cuti Chart Column (Right, 2/5 width on lg) -->
    <div class="lg:col-span-2 bg-white rounded-[3rem] shadow-sm border border-slate-100 p-8 flex flex-col justify-between overflow-hidden">
        <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-6">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Statistik Saldo Cuti</h2>
                <p class="text-xs text-slate-400 font-medium mt-1">Perbandingan kuota cuti sisa vs cuti terpakai (Total jatah: 12 hari).</p>
            </div>
            <span class="px-4 py-1.5 bg-rose-50 text-rose-600 rounded-xl text-[9px] font-extrabold uppercase tracking-widest border border-rose-100/50 shadow-sm">Quota Cuti</span>
        </div>

        <!-- Gauge Doughnut Chart Container -->
        <div class="relative h-72 w-full flex items-center justify-center">
            <div class="relative h-56 w-56">
                <canvas id="userCutiGaugeChart"></canvas>
                <!-- Center overlay text -->
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none" style="margin-top: 15px;">
                    <span class="text-4xl font-extrabold font-outfit text-slate-800">{{ $sisaCuti }}</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Hari Sisa</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Overtime Line Chart
        @if(count($data) > 0)
        const ctxOvertime = document.getElementById('userOvertimeChart');
        if(ctxOvertime) {
            new Chart(ctxOvertime, {
                type: 'line',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        label: 'Jam Lembur Disetujui',
                        data: {!! json_encode($data) !!},
                        backgroundColor: 'rgba(79, 70, 229, 0.05)', 
                        borderColor: 'rgba(79, 70, 229, 1)',      
                        borderWidth: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: 'rgba(79, 70, 229, 1)',
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 13, family: 'Outfit', weight: '700' },
                            bodyFont: { size: 12, family: 'Inter' },
                            padding: 12,
                            cornerRadius: 10,
                            displayColors: false,
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
                            grid: {
                                color: 'rgba(241, 245, 249, 1)',
                                borderDash: [5, 5]
                            },
                            ticks: { 
                                font: { family: 'Inter', weight: '500', size: 11 },
                                color: '#94a3b8',
                                padding: 6
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { 
                                font: { family: 'Inter', weight: '600', size: 11 },
                                color: '#94a3b8',
                                padding: 6
                            }
                        }
                    }
                }
            });
        }
        @endif

        // 2. Cuti Gauge Doughnut Chart
        const ctxCuti = document.getElementById('userCutiGaugeChart');
        if(ctxCuti) {
            new Chart(ctxCuti, {
                type: 'doughnut',
                data: {
                    labels: ['Sisa Cuti', 'Cuti Terpakai'],
                    datasets: [{
                        data: [{{ $sisaCuti }}, {{ $usedLeave }}],
                        backgroundColor: [
                            'rgba(79, 70, 229, 0.9)',    // Indigo Blue for remaining
                            'rgba(225, 29, 72, 0.9)'     // Rose Red for used
                        ],
                        borderColor: [
                            '#ffffff',
                            '#ffffff'
                        ],
                        borderWidth: 3,
                        weight: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '76%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                boxHeight: 12,
                                padding: 15,
                                font: { family: 'Outfit', weight: '600', size: 11 },
                                color: '#475569'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, family: 'Outfit', weight: '700' },
                            bodyFont: { size: 11, family: 'Inter' },
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': ' + context.parsed + ' Hari';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
