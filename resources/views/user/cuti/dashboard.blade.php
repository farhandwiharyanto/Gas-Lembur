@extends('user.layouts.app')

@section('header_title', 'Dashboard Cuti')

@section('content')
<div class="mb-8 px-4">
    <h1 class="text-3xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Dashboard Cuti</h1>
    <p class="text-sm text-slate-500 mt-1 font-medium italic opacity-80">Jatah Cuti Tahunan Anda adalah 12 hari per tahun berjalan.</p>
</div>

<!-- Cuti Metric Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 px-4">
    <!-- Total Quota (Indigo Slate) -->
    <div class="bg-slate-900 rounded-[2.5rem] shadow-[0_15px_40px_rgba(15,23,42,0.15)] p-10 flex flex-col justify-center items-center text-white transform hover:scale-[1.03] transition-all duration-500 border border-slate-800">
        <div class="text-slate-400 text-xs font-bold mb-3 uppercase tracking-[0.2em] opacity-80">Jatah Cuti Tahunan</div>
        <div class="text-5xl font-extrabold font-outfit tracking-tight text-white">12 <span class="text-base font-medium opacity-60 ml-1 text-slate-400">HARI</span></div>
    </div>
    <!-- Used Cuti (Rose Red) -->
    <div class="bg-rose-600 rounded-[2.5rem] shadow-[0_15px_40px_rgba(225,29,72,0.25)] p-10 flex flex-col justify-center items-center text-white transform hover:scale-[1.03] transition-all duration-500 border border-rose-500/30">
        <div class="text-rose-100 text-xs font-bold mb-3 uppercase tracking-[0.2em] opacity-80">Cuti Terpakai (Approved)</div>
        <div class="text-5xl font-extrabold font-outfit tracking-tight text-white">{{ $usedLeave }} <span class="text-base font-medium opacity-60 ml-1 text-rose-200">HARI</span></div>
    </div>
    <!-- Remaining Cuti (Emerald Green) -->
    <div class="bg-emerald-600 rounded-[2.5rem] shadow-[0_15px_40px_rgba(5,150,105,0.25)] p-10 flex flex-col justify-center items-center text-white transform hover:scale-[1.03] transition-all duration-500 border border-emerald-500/30">
        <div class="text-emerald-100 text-xs font-bold mb-3 uppercase tracking-[0.2em] opacity-80">Sisa Cuti Aktif</div>
        <div class="text-5xl font-extrabold font-outfit tracking-tight text-white">{{ $sisaCuti }} <span class="text-base font-medium opacity-60 ml-1 text-emerald-200">HARI</span></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 mb-8">
    <!-- Chart Column (Left 2 cols on lg) -->
    <div class="lg:col-span-2 bg-white rounded-[3rem] shadow-sm border border-slate-100 p-8 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-6">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Statistik Jatah Cuti</h2>
                <p class="text-xs text-slate-400 font-medium mt-1">Perbandingan jumlah cuti terpakai vs sisa saldo cuti Anda saat ini.</p>
            </div>
            <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-xl text-[9px] font-extrabold uppercase tracking-widest border border-indigo-100/50 shadow-sm">Chart Jatah</span>
        </div>
        
        <!-- Chart Canvas -->
        <div class="relative h-64 w-full">
            <canvas id="leaveUsageChart"></canvas>
        </div>
    </div>

    <!-- Actions / Info Card (Right 1 col on lg) -->
    <div class="bg-slate-900 text-slate-100 rounded-[3rem] shadow-xl p-8 flex flex-col justify-between border border-slate-800 relative overflow-hidden">
        <!-- Glow effect -->
        <div class="absolute -right-20 -top-20 w-44 h-44 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 w-44 h-44 rounded-full bg-emerald-500/10 blur-3xl"></div>

        <div class="relative z-10">
            <h2 class="text-xl font-extrabold font-outfit text-white uppercase tracking-tight mb-4">Aksi Pengajuan</h2>
            <p class="text-xs text-slate-400 leading-relaxed mb-6">Ajukan cuti kerja Anda dengan mudah. Perhitungan durasi hari cuti akan otomatis mengecualikan hari Sabtu, Minggu, dan Libur Akhir Pekan.</p>
            
            <div class="space-y-4 mb-8">
                <div class="flex items-center space-x-3 text-xs bg-slate-800/50 p-4 rounded-2xl border border-slate-700/30">
                    <svg class="w-5 h-5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Ajukan minimal 3 hari sebelum tanggal mulai cuti.</span>
                </div>
                <div class="flex items-center space-x-3 text-xs bg-slate-800/50 p-4 rounded-2xl border border-slate-700/30">
                    <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Status pengajuan terpantau real-time di riwayat.</span>
                </div>
            </div>
        </div>

        <div class="relative z-10 pt-4 border-t border-slate-800">
            <a href="{{ route('user.cuti.create') }}" class="w-full flex items-center justify-center py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl transition-all duration-300 font-bold text-sm tracking-wide shadow-lg shadow-indigo-900/30 hover:shadow-indigo-900/40">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Pengajuan Cuti
            </a>
        </div>
    </div>
</div>

<!-- 5 Recent Leaves Table -->
<div class="px-4 mb-10">
    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 p-8 overflow-hidden">
        <div class="flex items-center justify-between mb-8 pb-6 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-extrabold text-slate-800 font-outfit uppercase tracking-tight">Pengajuan Cuti Terakhir</h2>
                <p class="text-xs text-slate-400 font-medium mt-1">Daftar 5 berkas pengajuan cuti Anda paling baru.</p>
            </div>
            <a href="{{ route('user.cuti.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors uppercase tracking-widest bg-indigo-50 px-4 py-2 rounded-xl border border-indigo-100/50">Semua Riwayat</a>
        </div>

        <div class="overflow-x-auto rounded-[2rem] border border-slate-100">
            <table class="w-full text-left border-collapse min-w-full">
                <thead>
                    <tr class="bg-slate-900 text-white text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4 rounded-tl-[2rem]">Tanggal Mulai</th>
                        <th class="px-6 py-4">Tanggal Selesai</th>
                        <th class="px-6 py-4 text-center">Durasi</th>
                        <th class="px-6 py-4">Alasan Cuti</th>
                        <th class="px-6 py-4 text-center rounded-tr-[2rem]">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium">
                    @forelse($recentLeaves as $leave)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-slate-800 font-bold">
                                {{ \Carbon\Carbon::parse($leave->tanggal_mulai)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 text-slate-800">
                                {{ \Carbon\Carbon::parse($leave->tanggal_selesai)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-700">
                                {{ $leave->total_hari }} Hari
                            </td>
                            <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="{{ $leave->alasan }}">
                                {{ $leave->alasan }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($leave->status === 'approved')
                                    <span class="px-3.5 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-extrabold rounded-xl border border-emerald-100/80 shadow-sm uppercase tracking-wider">Disetujui</span>
                                @elseif($leave->status === 'rejected')
                                    <span class="px-3.5 py-1.5 bg-red-50 text-red-700 text-xs font-extrabold rounded-xl border border-red-100/80 shadow-sm uppercase tracking-wider">Ditolak</span>
                                @else
                                    <span class="px-3.5 py-1.5 bg-amber-50 text-amber-700 text-xs font-extrabold rounded-xl border border-amber-100/80 shadow-sm uppercase tracking-wider animate-pulse">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">
                                Belum ada pengajuan cuti yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('leaveUsageChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Cuti Terpakai', 'Sisa Cuti'],
                    datasets: [{
                        label: 'Hari Kerja',
                        data: [{{ $usedLeave }}, {{ $sisaCuti }}],
                        backgroundColor: [
                            'rgba(225, 29, 72, 0.85)',   // Rose Red for used
                            'rgba(16, 185, 129, 0.85)'   // Emerald for remaining
                        ],
                        borderColor: [
                            'rgba(225, 29, 72, 1)',
                            'rgba(16, 185, 129, 1)'
                        ],
                        borderWidth: 2,
                        borderRadius: 16,
                        borderSkipped: false,
                        barThickness: 50
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
                                    return context.parsed.y + ' Hari';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 12,
                            grid: {
                                color: 'rgba(241, 245, 249, 1)',
                                borderDash: [5, 5]
                            },
                            ticks: { 
                                font: { family: 'Inter', weight: '500' },
                                color: '#94a3b8',
                                stepSize: 2
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { 
                                font: { family: 'Outfit', weight: '600', size: 13 },
                                color: '#475569'
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
