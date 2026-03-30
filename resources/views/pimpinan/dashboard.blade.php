@extends('pimpinan.layouts.app')

@section('header_title', 'Dashboard Pimpinan')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Ringkasan Lembur Karyawan</h1>
    <p class="text-sm text-slate-500 mt-1 font-medium italic opacity-80">Pantau statistik karyawan dengan intensitas lembur tertinggi yang telah disetujui (Approved).</p>
</div>

<!-- Dashboard Metric Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-indigo-600 rounded-[2rem] shadow-[0_10px_30px_rgba(79,70,229,0.3)] p-8 flex flex-col justify-center items-center text-white transform hover:scale-105 transition-transform duration-300">
        <div class="text-indigo-100 text-xs font-bold mb-2 uppercase tracking-widest opacity-80">Total Jam (Disetujui)</div>
        <div class="text-4xl font-extrabold font-outfit">{{ $totalApproved }} <span class="text-sm font-medium opacity-60">JAM</span></div>
    </div>
    <div class="bg-slate-900 rounded-[2rem] shadow-[0_10px_30px_rgba(15,23,42,0.2)] p-8 flex flex-col justify-center items-center text-white transform hover:scale-105 transition-transform duration-300 border border-slate-800">
        <div class="text-slate-400 text-xs font-bold mb-2 uppercase tracking-widest opacity-80">Total Jam (Menunggu)</div>
        <div class="text-4xl font-extrabold font-outfit text-yellow-400">{{ $totalWaiting }} <span class="text-sm font-medium opacity-60 text-slate-500">JAM</span></div>
    </div>
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 flex flex-col justify-center items-center transform hover:scale-105 transition-transform duration-300">
        <div class="text-slate-500 text-xs font-bold mb-2 uppercase tracking-widest opacity-80">Total Jam (Ditolak)</div>
        <div class="text-4xl font-extrabold font-outfit text-slate-800">{{ $totalRejected }} <span class="text-sm font-medium opacity-40 text-slate-400">JAM</span></div>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10 mb-8 overflow-hidden">
    <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-6">
        <h2 class="text-xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Top 10 Karyawan Lembur</h2>
        <span class="px-4 py-1 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold uppercase tracking-widest border border-indigo-100">Berdasarkan Total Jam Disetujui</span>
    </div>
    
    @if(count($data) > 0)
        <!-- Chart Container -->
        <div class="relative h-96 w-full">
            <canvas id="overtimeChart"></canvas>
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <p>Belum ada data lembur yang disetujui untuk ditampilkan.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
@if(count($data) > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('overtimeChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        label: 'Total Lembur Disetujui',
                        data: {!! json_encode($data) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.6)', // Emerald 500
                        borderColor: 'rgba(5, 150, 105, 1)',      // Emerald 600
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Pengajuan Disetujui';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 } // Integer only
                        }
                    }
                }
            });
        }
    });
</script>
@endif
@endpush
