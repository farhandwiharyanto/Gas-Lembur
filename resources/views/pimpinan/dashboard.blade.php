@extends('pimpinan.layouts.app')

@section('header_title', 'Dashboard Pimpinan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Ringkasan Lembur Karyawan</h1>
    <p class="text-sm text-gray-500 mt-1">Pantau statistik karyawan dengan intensitas lembur tertinggi yang telah disetujui (Approved).</p>
</div>

<!-- Dashboard Metric Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center items-center">
        <div class="text-gray-500 text-sm font-semibold mb-1">Total Jam (Disetujui)</div>
        <div class="text-3xl font-bold text-emerald-600">{{ $totalApproved }} <span class="text-lg font-medium text-gray-400">Jam</span></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center items-center">
        <div class="text-gray-500 text-sm font-semibold mb-1">Total Jam (Menunggu)</div>
        <div class="text-3xl font-bold text-yellow-500">{{ $totalWaiting }} <span class="text-lg font-medium text-gray-400">Jam</span></div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-center items-center">
        <div class="text-gray-500 text-sm font-semibold mb-1">Total Jam (Ditolak)</div>
        <div class="text-3xl font-bold text-red-500">{{ $totalRejected }} <span class="text-lg font-medium text-gray-400">Jam</span></div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Top 10 Karyawan Lembur Tertinggi</h2>
    
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
