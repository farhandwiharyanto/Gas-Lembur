@extends('admin.layouts.app')

@section('header_title', 'Dashboard Utama')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Stat 1 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="p-4 rounded-full bg-blue-50 text-blue-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Orang Lembur</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
        </div>
    </div>
    
    <!-- Stat 2 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="p-4 rounded-full bg-green-50 text-green-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Lemburan Approved</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalApproved }}</p>
        </div>
    </div>
    
    <!-- Stat 3 -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
        <div class="p-4 rounded-full bg-yellow-50 text-yellow-600 mr-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Menunggu Persetujuan</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalPending }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Chart Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Grafik Jumlah Lembur per Bagian</h2>
        <div class="relative h-72 w-full">
            <canvas id="lemburChart"></canvas>
        </div>
    </div>
    
    <!-- List Ringkasan Orang -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Karyawan Sering Lembur</h2>
        <div class="overflow-y-auto max-h-72">
            <ul class="divide-y divide-gray-100">
                @forelse($topUsers as $user)
                <li class="py-3 flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold mr-3 text-xs">
                            {{ substr($user->nama, 0, 2) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $user->nama }}</p>
                            <p class="text-xs text-gray-500">{{ $user->bagian }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold">
                        {{ $user->total }} Kali
                    </span>
                </li>
                @empty
                <li class="py-4 text-center text-sm text-gray-500">Belum ada data lembur.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('lemburChart').getContext('2d');
        const bagianLabels = {!! json_encode($chartLabels) !!};
        const bagianData = {!! json_encode($chartData) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: bagianLabels,
                datasets: [{
                    label: 'Jumlah Lembur',
                    data: bagianData,
                    backgroundColor: 'rgba(37, 99, 235, 0.8)',
                    borderColor: 'rgba(37, 99, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endpush
