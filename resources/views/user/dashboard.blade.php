@extends('user.layouts.app')

@section('header_title', 'Dashboard Karyawan')

@section('content')
<div class="mb-8 px-4">
    <h1 class="text-3xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Dashboard Ringkasan</h1>
    <p class="text-sm text-slate-500 mt-1 font-medium italic opacity-80">Selamat datang kembali! Berikut adalah ringkasan jam lembur Anda.</p>
</div>

<!-- Dashboard Metric Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 px-4">
    <!-- Approved (Green) -->
    <div class="bg-green-600 rounded-[2.5rem] shadow-[0_15px_40px_rgba(22,163,74,0.25)] p-10 flex flex-col justify-center items-center text-white transform hover:scale-[1.03] transition-all duration-500 border border-green-500/30">
        <div class="text-green-100 text-xs font-bold mb-3 uppercase tracking-[0.2em] opacity-80">Total Jam (Disetujui)</div>
        <div class="text-5xl font-extrabold font-outfit tracking-tight">{{ (int)$totalApproved }} <span class="text-base font-medium opacity-60 ml-1">JAM</span></div>
    </div>
    <!-- Waiting (Yellow) -->
    <div class="bg-yellow-500 rounded-[2.5rem] shadow-[0_15px_40px_rgba(234,179,8,0.2)] p-10 flex flex-col justify-center items-center text-white transform hover:scale-[1.03] transition-all duration-500 border border-yellow-400">
        <div class="text-yellow-100 text-xs font-bold mb-3 uppercase tracking-[0.2em] opacity-80">Total Jam (Menunggu)</div>
        <div class="text-5xl font-extrabold font-outfit tracking-tight text-white">{{ (int)$totalWaiting }} <span class="text-base font-medium opacity-60 ml-1 text-white/70">JAM</span></div>
    </div>
    <!-- Rejected (Red) -->
    <div class="bg-red-600 rounded-[2.5rem] shadow-[0_15px_40px_rgba(220,38,38,0.2)] p-10 flex flex-col justify-center items-center text-white transform hover:scale-[1.03] transition-all duration-500 border border-red-500">
        <div class="text-red-100 text-xs font-bold mb-3 uppercase tracking-[0.2em] opacity-80">Total Jam (Ditolak)</div>
        <div class="text-5xl font-extrabold font-outfit tracking-tight text-white">{{ (int)$totalRejected }} <span class="text-base font-medium opacity-60 ml-1 text-white/70">JAM</span></div>
    </div>
</div>

<div class="px-4">
    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 p-10 mb-8 overflow-hidden">
        <div class="flex items-center justify-between mb-10 border-b border-slate-100 pb-8">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Grafik Intensitas Lembur</h2>
                <p class="text-sm text-slate-400 font-medium mt-1">Akumulasi jam lembur yang telah disetujui dalam 6 bulan terakhir.</p>
            </div>
            <span class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-2xl text-[10px] font-extrabold uppercase tracking-widest border border-indigo-100/50 shadow-sm">Bulan vs Total Jam</span>
        </div>
        
        @if(count($data) > 0)
            <!-- Chart Container -->
            <div class="relative h-[28rem] w-full">
                <canvas id="userOvertimeChart"></canvas>
            </div>
        @else
            <div class="text-center py-20 text-slate-400 bg-slate-50/50 rounded-[2rem] border-2 border-dashed border-slate-100">
                <svg class="mx-auto h-20 w-20 text-slate-200 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 3a1 1 0 100 2h2a1 1 0 100-2h-2zM4.5 8.25a.75.75 0 000 1.5h15a.75.75 0 000-1.5H4.5zM3 12a1 1 0 011-1h16a1 1 0 011 1v6.75A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75V12z" />
                </svg>
                <p class="text-lg font-bold text-slate-500">Belum Ada Data Statistik</p>
                <p class="text-sm max-w-xs mx-auto mt-2">Data grafik akan muncul setelah pengajuan lembur Anda disetujui oleh Pimpinan.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if(count($data) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('userOvertimeChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        label: 'Total Jam Lembur Disetujui',
                        data: {!! json_encode($data) !!},
                        backgroundColor: 'rgba(79, 70, 229, 0.1)', 
                        borderColor: 'rgba(79, 70, 229, 1)',      
                        borderWidth: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: 'rgba(79, 70, 229, 1)',
                        pointBorderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8,
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
                            titleFont: { size: 14, family: 'Outfit', weight: '700' },
                            bodyFont: { size: 13, family: 'Inter' },
                            padding: 15,
                            cornerRadius: 12,
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
                                font: { family: 'Inter', weight: '500' },
                                color: '#94a3b8',
                                padding: 10
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { 
                                font: { family: 'Inter', weight: '600' },
                                color: '#94a3b8',
                                padding: 10
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endif
@endpush
