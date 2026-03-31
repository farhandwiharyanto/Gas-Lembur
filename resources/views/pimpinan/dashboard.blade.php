@extends('pimpinan.layouts.app')

@section('header_title', 'Dashboard Pimpinan')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Ringkasan Lembur Karyawan</h1>
    <p class="text-sm text-slate-500 mt-1 font-medium italic opacity-80">Pantau statistik karyawan dengan intensitas lembur tertinggi yang telah disetujui (Approved).</p>
</div>

<!-- Dashboard Metric Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <!-- Approved (Green) -->
    <div class="bg-green-600 rounded-[2rem] shadow-[0_10px_30px_rgba(22,163,74,0.3)] p-8 flex flex-col justify-center items-center text-white transform hover:scale-105 transition-transform duration-300">
        <div class="text-green-100 text-xs font-bold mb-2 uppercase tracking-widest opacity-80">Total Jam (Disetujui)</div>
        <div class="text-4xl font-extrabold font-outfit">{{ (int)$totalApproved }} <span class="text-sm font-medium opacity-60">JAM</span></div>
    </div>
    <!-- Waiting (Yellow) -->
    <div class="bg-yellow-500 rounded-[2rem] shadow-[0_10px_30px_rgba(234,179,8,0.2)] p-8 flex flex-col justify-center items-center text-white transform hover:scale-105 transition-transform duration-300 border border-yellow-400">
        <div class="text-yellow-100 text-xs font-bold mb-2 uppercase tracking-widest opacity-80">Total Jam (Menunggu)</div>
        <div class="text-4xl font-extrabold font-outfit text-white">{{ (int)$totalWaiting }} <span class="text-sm font-medium opacity-60 text-white/70">JAM</span></div>
    </div>
    <!-- Rejected (Red) -->
    <div class="bg-red-600 rounded-[2rem] shadow-[0_10px_30px_rgba(220,38,38,0.2)] p-8 flex flex-col justify-center items-center text-white transform hover:scale-105 transition-transform duration-300 border border-red-500">
        <div class="text-red-100 text-xs font-bold mb-2 uppercase tracking-widest opacity-80">Total Jam (Ditolak)</div>
        <div class="text-4xl font-extrabold font-outfit text-white">{{ (int)$totalRejected }} <span class="text-sm font-medium opacity-60 text-white/70">JAM</span></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Chart Container -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 overflow-hidden flex flex-col">
        <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-6">
            <h2 class="text-xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Grafik Lembur 10 Teratas</h2>
        </div>
        
        @if(count($data) > 0)
            <div class="relative h-96 w-full mt-auto">
                <canvas id="overtimeChart"></canvas>
            </div>
        @else
            <div class="text-center py-12 text-gray-500 my-auto">
                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <p>Belum ada data lembur yang disetujui.</p>
            </div>
        @endif
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 overflow-hidden flex flex-col">
        <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-6">
            <h2 class="text-xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Daftar Top 10 Karyawan</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Karyawan</th>
                        <th class="px-4 py-3 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Jam Disetujui</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($top10 as $item)
                    <tr>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-slate-700">{{ $item->employee_name }}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-indigo-600 text-right">{{ (int)$item->total_jam }} JAM</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="px-4 py-10 text-center text-sm text-slate-400 font-medium">Belum ada data tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
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
