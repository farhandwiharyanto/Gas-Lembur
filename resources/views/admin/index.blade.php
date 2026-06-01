@extends('admin.layouts.app')

@section('header_title', 'Dashboard Utama')

@section('content')
<div class="mb-8 px-4">
    <h1 class="text-3xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight font-outfit">Ringkasan Operasional Perusahaan</h1>
    <p class="text-sm text-slate-500 mt-1 font-medium italic opacity-80">Pantau seluruh statistik operasional lembur dan cuti karyawan di seluruh departemen secara real-time.</p>
</div>

<!-- ========================================== -->
<!-- SECTION I: OVERTIME (LEMBUR) OPERATIONALS -->
<!-- ========================================== -->
<div class="mb-6 px-4">
    <h2 class="text-2xl font-black text-slate-800 font-outfit uppercase tracking-tight pb-2 border-b border-slate-200/50">I. Operasional Lembur Perusahaan</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 px-4">
    <!-- Stat 1: Total Orang Lembur -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="p-6 flex items-center border-b border-gray-50 bg-slate-50/50">
            <div class="p-4 rounded-full bg-blue-50 text-blue-600 mr-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Total Orang Lembur</p>
                <p class="text-3xl font-black text-slate-800 font-outfit">{{ $totalUsers }}</p>
            </div>
        </div>
        <div class="p-4 flex-1">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-2">Top 5 Karyawan (Kumulatif)</p>
            <table class="w-full text-left text-xs font-medium">
                <thead>
                    <tr class="text-[9px] text-slate-400 uppercase border-b border-slate-100">
                        <th class="pb-2 font-bold">Nama</th>
                        <th class="pb-2 font-bold text-right">Jam</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($top5All as $user)
                    <tr>
                        <td class="py-2 text-slate-700 font-bold truncate max-w-[120px]">{{ $user->employee_name }}</td>
                        <td class="py-2 text-blue-600 font-black text-right">{{ round($user->total_jam) }} JAM</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Stat 2: Total Lemburan Approved -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="p-6 flex items-center border-b border-gray-50 bg-slate-50/50">
            <div class="p-4 rounded-full bg-green-50 text-green-600 mr-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Total Lemburan Disetujui</p>
                <p class="text-3xl font-black text-slate-800 font-outfit">{{ $totalApproved }}</p>
            </div>
        </div>
        <div class="p-4 flex-1">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-2">Top 5 Disetujui (Jam)</p>
            <table class="w-full text-left text-xs font-medium">
                <thead>
                    <tr class="text-[9px] text-slate-400 uppercase border-b border-slate-100">
                        <th class="pb-2 font-bold">Nama</th>
                        <th class="pb-2 font-bold text-right">Jam</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($top5Approved as $user)
                    <tr>
                        <td class="py-2 text-slate-700 font-bold truncate max-w-[120px]">{{ $user->employee_name }}</td>
                        <td class="py-2 text-green-600 font-black text-right">{{ round($user->total_jam) }} JAM</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Stat 3: Menunggu Persetujuan -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="p-6 flex items-center border-b border-gray-50 bg-slate-50/50">
            <div class="p-4 rounded-full bg-yellow-50 text-yellow-600 mr-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Menunggu Persetujuan</p>
                <p class="text-3xl font-black text-slate-800 font-outfit">{{ $totalPending }}</p>
            </div>
        </div>
        <div class="p-4 flex-1">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-2">Top 5 Menunggu (Jam)</p>
            <table class="w-full text-left text-xs font-medium">
                <thead>
                    <tr class="text-[9px] text-slate-400 uppercase border-b border-slate-100">
                        <th class="pb-2 font-bold">Nama</th>
                        <th class="pb-2 font-bold text-right">Jam</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($top5Pending as $user)
                    <tr>
                        <td class="py-2 text-slate-700 font-bold truncate max-w-[120px]">{{ $user->employee_name }}</td>
                        <td class="py-2 text-yellow-600 font-black text-right">{{ round($user->total_jam) }} JAM</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 mb-12 px-4">
    <!-- Lembur Chart -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 overflow-hidden">
        <h3 class="text-lg font-bold text-slate-800 mb-6 font-outfit uppercase tracking-tight pb-3 border-b border-slate-50">Grafik Jumlah Lembur per Bagian</h3>
        <div class="relative h-72 w-full">
            <canvas id="lemburChart"></canvas>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- SECTION II: LEAVE (CUTI) OPERATIONALS -->
<!-- ========================================== -->
<div class="mb-6 px-4">
    <h2 class="text-2xl font-black text-slate-800 font-outfit uppercase tracking-tight pb-2 border-b border-slate-200/50">II. Operasional Cuti Perusahaan</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 px-4">
    <!-- Stat 1: Total Orang Cuti -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="p-6 flex items-center border-b border-gray-50 bg-slate-50/50">
            <div class="p-4 rounded-full bg-indigo-50 text-indigo-600 mr-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Total Orang Cuti</p>
                <p class="text-3xl font-black text-slate-800 font-outfit">{{ $totalCutiUsers }}</p>
            </div>
        </div>
        <div class="p-4 flex-1">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-2">Top 5 Karyawan Cuti (Kumulatif)</p>
            <table class="w-full text-left text-xs font-medium">
                <thead>
                    <tr class="text-[9px] text-slate-400 uppercase border-b border-slate-100">
                        <th class="pb-2 font-bold">Nama</th>
                        <th class="pb-2 font-bold text-right">Hari</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($top5CutiAll as $user)
                    <tr>
                        <td class="py-2 text-slate-700 font-bold truncate max-w-[120px]">{{ $user->employee_name }}</td>
                        <td class="py-2 text-indigo-600 font-black text-right">{{ round($user->total_hari) }} HARI</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Stat 2: Total Cuti Approved -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="p-6 flex items-center border-b border-gray-50 bg-slate-50/50">
            <div class="p-4 rounded-full bg-emerald-50 text-emerald-600 mr-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Pengajuan Cuti Disetujui</p>
                <p class="text-3xl font-black text-slate-800 font-outfit">{{ $totalCutiApproved }}</p>
            </div>
        </div>
        <div class="p-4 flex-1">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-2">Top 5 Disetujui (Hari)</p>
            <table class="w-full text-left text-xs font-medium">
                <thead>
                    <tr class="text-[9px] text-slate-400 uppercase border-b border-slate-100">
                        <th class="pb-2 font-bold">Nama</th>
                        <th class="pb-2 font-bold text-right">Hari</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($top5CutiApproved as $user)
                    <tr>
                        <td class="py-2 text-slate-700 font-bold truncate max-w-[120px]">{{ $user->employee_name }}</td>
                        <td class="py-2 text-emerald-600 font-black text-right">{{ round($user->total_hari) }} HARI</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Stat 3: Cuti Menunggu Persetujuan -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="p-6 flex items-center border-b border-gray-50 bg-slate-50/50">
            <div class="p-4 rounded-full bg-rose-50 text-rose-600 mr-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Cuti Menunggu Persetujuan</p>
                <p class="text-3xl font-black text-slate-800 font-outfit">{{ $totalCutiPending }}</p>
            </div>
        </div>
        <div class="p-4 flex-1">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 px-2">Top 5 Menunggu (Hari)</p>
            <table class="w-full text-left text-xs font-medium">
                <thead>
                    <tr class="text-[9px] text-slate-400 uppercase border-b border-slate-100">
                        <th class="pb-2 font-bold">Nama</th>
                        <th class="pb-2 font-bold text-right">Hari</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($top5CutiPending as $user)
                    <tr>
                        <td class="py-2 text-slate-700 font-bold truncate max-w-[120px]">{{ $user->employee_name }}</td>
                        <td class="py-2 text-rose-600 font-black text-right">{{ round($user->total_hari) }} HARI</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 mb-8 px-4">
    <!-- Cuti Chart -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 overflow-hidden">
        <h3 class="text-lg font-bold text-slate-800 mb-6 font-outfit uppercase tracking-tight pb-3 border-b border-slate-50">Grafik Jumlah Hari Cuti per Bagian</h3>
        <div class="relative h-72 w-full">
            <canvas id="cutiChart"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Overtime Chart
        const ctxLembur = document.getElementById('lemburChart').getContext('2d');
        const bagianLabels = {!! json_encode($chartLabels) !!};
        const bagianData = {!! json_encode($chartData) !!};

        new Chart(ctxLembur, {
            type: 'bar',
            data: {
                labels: bagianLabels,
                datasets: [{
                    label: 'Jumlah Lembur',
                    data: bagianData,
                    backgroundColor: 'rgba(37, 99, 235, 0.75)', // Blue
                    borderColor: 'rgba(37, 99, 235, 1)',
                    borderWidth: 1.5,
                    borderRadius: 6
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

        // 2. Cuti Chart
        const ctxCuti = document.getElementById('cutiChart').getContext('2d');
        const cutiLabels = {!! json_encode($chartCutiLabels) !!};
        const cutiData = {!! json_encode($chartCutiData) !!};

        new Chart(ctxCuti, {
            type: 'bar',
            data: {
                labels: cutiLabels,
                datasets: [{
                    label: 'Jumlah Hari Cuti',
                    data: cutiData,
                    backgroundColor: 'rgba(79, 70, 229, 0.75)', // Indigo
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1.5,
                    borderRadius: 6
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
