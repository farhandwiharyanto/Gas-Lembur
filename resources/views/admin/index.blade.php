@extends('admin.layouts.app')

@section('header_title', 'Dashboard Utama')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Stat 1: Total Orang Lembur -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
        <div class="p-6 flex items-center border-b border-gray-50">
            <div class="p-4 rounded-full bg-blue-50 text-blue-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Orang Lembur</p>
                <p class="text-3xl font-extrabold text-gray-800">{{ $totalUsers }}</p>
            </div>
        </div>
        <div class="p-4 flex-1">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 px-2">Top 5 Karyawan (Kumulatif)</p>
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-[10px] text-gray-400 uppercase border-b border-gray-50">
                        <th class="pb-2 font-semibold">Nama</th>
                        <th class="pb-2 font-semibold text-right">Jam</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($top5All as $user)
                    <tr>
                        <td class="py-2 text-xs font-semibold text-gray-700 truncate max-w-[120px]">{{ $user->employee_name }}</td>
                        <td class="py-2 text-xs font-bold text-blue-600 text-right">{{ round($user->total_jam) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Stat 2: Total Lemburan Approved -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
        <div class="p-6 flex items-center border-b border-gray-50">
            <div class="p-4 rounded-full bg-green-50 text-green-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Lemburan Disetujui</p>
                <p class="text-3xl font-extrabold text-gray-800">{{ $totalApproved }}</p>
            </div>
        </div>
        <div class="p-4 flex-1">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 px-2">Top 5 Disetujui (Jam)</p>
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-[10px] text-gray-400 uppercase border-b border-gray-50">
                        <th class="pb-2 font-semibold">Nama</th>
                        <th class="pb-2 font-semibold text-right">Jam</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($top5Approved as $user)
                    <tr>
                        <td class="py-2 text-xs font-semibold text-gray-700 truncate max-w-[120px]">{{ $user->employee_name }}</td>
                        <td class="py-2 text-xs font-bold text-green-600 text-right">{{ round($user->total_jam) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Stat 3: Menunggu Persetujuan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
        <div class="p-6 flex items-center border-b border-gray-50">
            <div class="p-4 rounded-full bg-yellow-50 text-yellow-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Menunggu Persetujuan</p>
                <p class="text-3xl font-extrabold text-gray-800">{{ $totalPending }}</p>
            </div>
        </div>
        <div class="p-4 flex-1">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 px-2">Top 5 Menunggu (Jam)</p>
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-[10px] text-gray-400 uppercase border-b border-gray-50">
                        <th class="pb-2 font-semibold">Nama</th>
                        <th class="pb-2 font-semibold text-right">Jam</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($top5Pending as $user)
                    <tr>
                        <td class="py-2 text-xs font-semibold text-gray-700 truncate max-w-[120px]">{{ $user->employee_name }}</td>
                        <td class="py-2 text-xs font-bold text-yellow-600 text-right">{{ round($user->total_jam) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6">
    <!-- Chart Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 font-outfit uppercase tracking-tight">Grafik Jumlah Lembur per Bagian</h2>
        <div class="relative h-72 w-full">
            <canvas id="lemburChart"></canvas>
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
