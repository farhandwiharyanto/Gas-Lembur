@extends('user.layouts.app')

@section('header_title', 'Perhitungan & Gaji Lembur')

@section('content')
<div class="py-4">
    <!-- Header visual with Aturan Potongan Istirahat integrated inside on the right -->
    <div class="mb-8 p-6 bg-slate-900 rounded-[2rem] border border-slate-800 shadow-xl text-white relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
        <div class="absolute left-1/3 bottom-0 w-48 h-48 bg-indigo-500/5 rounded-full blur-2xl -ml-16 -mb-16"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="max-w-xl">
                <span class="px-3 py-1 text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 rounded-full uppercase tracking-wider">Peran Karyawan</span>
                <h2 class="text-3xl font-extrabold tracking-tight mt-2 font-outfit uppercase">PERHITUNGAN & GAJI LEMBUR</h2>
                <p class="text-slate-400 text-sm mt-1">
                    Informasi terperinci upah kerja lembur efektif Anda yang dihitung otomatis berdasarkan data kehadiran nyata dan aturan perusahaan.
                </p>
            </div>
            
            <!-- Compact Aturan Potongan Istirahat (Top Right Glassmorphism Card) -->
            <div class="bg-white/5 border border-white/10 p-4 rounded-2xl backdrop-blur-sm self-start md:self-auto max-w-xs w-full shadow-lg">
                <h4 class="text-[10px] font-bold text-indigo-300 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Aturan Potongan Istirahat
                </h4>
                <div class="rounded-xl overflow-hidden bg-slate-950/40 border border-white/5">
                    <table class="min-w-full divide-y divide-white/5 text-[9px] text-slate-300">
                        <thead class="bg-white/5 font-bold text-slate-400 uppercase tracking-wider text-[8px]">
                            <tr>
                                <th class="px-2.5 py-1 text-left">Durasi Lembur</th>
                                <th class="px-2.5 py-1 text-right">Potongan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr>
                                <td class="px-2.5 py-1 font-semibold">&ge; 20 Jam</td>
                                <td class="px-2.5 py-1 text-right text-red-400 font-bold">-4 Jam</td>
                            </tr>
                            <tr>
                                <td class="px-2.5 py-1 font-semibold">&ge; 15 Jam</td>
                                <td class="px-2.5 py-1 text-right text-red-400 font-bold">-3 Jam</td>
                            </tr>
                            <tr>
                                <td class="px-2.5 py-1 font-semibold">&ge; 10 Jam</td>
                                <td class="px-2.5 py-1 text-right text-red-400 font-bold">-2 Jam</td>
                            </tr>
                            <tr>
                                <td class="px-2.5 py-1 font-semibold">&ge; 5 Jam</td>
                                <td class="px-2.5 py-1 text-right text-red-400 font-bold">-1 Jam</td>
                            </tr>
                            <tr>
                                <td class="px-2.5 py-1 font-semibold text-slate-400">&lt; 5 Jam</td>
                                <td class="px-2.5 py-1 text-right text-slate-400">0 Jam</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Metrics Row (Gaji Pokok Locked, Uang Lembur Approved & Waiting) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Gaji Pokok (Locked/Patented to 5,880,000) -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 flex flex-col justify-between text-white relative overflow-hidden shadow-md">
            <div class="absolute right-2 top-2 text-white/5">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
            </div>
            <div class="relative z-10">
                <span class="text-[10px] text-indigo-300 font-bold uppercase tracking-widest block mb-1">Gaji Pokok (Paten)</span>
                <div class="text-2xl font-black font-outfit text-white">Rp 5.880.000</div>
                <div class="text-[10px] text-slate-400 mt-2">
                    Rate per Jam: <span class="text-slate-200 font-semibold">Rp 33.988,44</span>
                </div>
            </div>
        </div>

        <!-- Total Uang Lembur (Disetujui) -->
        <div class="bg-green-600 rounded-3xl p-6 flex flex-col justify-between text-white shadow-md">
            <div>
                <span class="text-[10px] text-green-100 font-bold uppercase tracking-widest block mb-1">Upah Lembur (Disetujui)</span>
                <div class="text-2xl font-black font-outfit">Rp {{ number_format($totalApprovedUang, 0, ',', '.') }}</div>
                <div class="text-[10px] text-green-100/80 mt-2">
                    Siap dibayarkan oleh bagian keuangan
                </div>
            </div>
        </div>

        <!-- Total Uang Lembur (Menunggu) -->
        <div class="bg-yellow-500 rounded-3xl p-6 flex flex-col justify-between text-white shadow-md">
            <div>
                <span class="text-[10px] text-yellow-100 font-bold uppercase tracking-widest block mb-1">Estimasi Upah (Menunggu)</span>
                <div class="text-2xl font-black font-outfit">Rp {{ number_format($totalWaitingUang, 0, ',', '.') }}</div>
                <div class="text-[10px] text-yellow-100/80 mt-2">
                    Menunggu persetujuan pimpinan ({{ number_format($totalWaitingMultiplier, 2, ',', '.') }}x)
                </div>
            </div>
        </div>

        <!-- Total Take Home Pay (Premium Gradient Card) -->
        <div class="bg-gradient-to-br from-indigo-900 via-indigo-950 to-slate-950 border border-indigo-800/40 rounded-3xl p-6 flex flex-col justify-between text-white shadow-lg relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl -mr-8 -mt-8"></div>
            <div class="relative z-10 flex flex-col justify-between h-full space-y-3">
                <div>
                    <span class="text-[10px] text-indigo-300 font-bold uppercase tracking-widest block mb-1">Total Pendapatan Bulanan</span>
                    
                    @php
                        $bpjsKesehatan = 58800;
                        $jht = 117600;
                        $jp = 58800;
                        $totalDeductions = $bpjsKesehatan + $jht + $jp; // Rp 235.200
                        
                        $netApproved = ($gajiPokok + $totalApprovedUang) - $totalDeductions;
                        $netEstimated = ($gajiPokok + $totalApprovedUang + $totalWaitingUang) - $totalDeductions;
                    @endphp
                    
                    <div class="mt-2 space-y-1">
                        <span class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wider block">Potongan Wajib Bulanan (BPJS & JHT)</span>
                        <div class="text-xs text-red-400 font-bold">
                            -Rp {{ number_format($totalDeductions, 0, ',', '.') }}
                            <span class="text-[8px] text-slate-500 font-medium block mt-0.5">(BPJS Kes: 58.8k | JHT: 117.6k | JP: 58.8k)</span>
                        </div>
                    </div>
                </div>
                
                <div class="pt-2 border-t border-indigo-500/20">
                    <span class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wider block">Gaji Bersih / Take Home Pay (Disetujui)</span>
                    <div class="text-lg font-black font-outfit text-white">Rp {{ number_format($netApproved, 0, ',', '.') }}</div>
                </div>
                
                <div class="pt-2 border-t border-indigo-500/20">
                    <span class="text-[8px] text-slate-400 font-extrabold uppercase tracking-wider block">Estimasi Bersih Akhir (Jika Disetujui)</span>
                    <div class="text-sm font-extrabold font-outfit text-emerald-400">Rp {{ number_format($netEstimated, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overtimes Table (100% Full Width Card - Absolutely No Horizontal Scroll on Desktop) -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <div>
                <h3 class="text-lg font-bold text-slate-800 font-outfit uppercase tracking-tight">Rincian Perhitungan Per Pengajuan</h3>
                <p class="text-xs text-slate-400 mt-1 font-medium uppercase tracking-wider">Perhitungan nominal berdasarkan waktu lembur nyata Anda.</p>
            </div>
            <div>
                <input type="month" id="monthFilter" value="{{ $selectedMonth ?? '' }}" onchange="window.location.href='?month=' + this.value" class="px-3 py-2 text-xs rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-slate-600 font-medium bg-white shadow-sm">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 table-auto">
                <thead class="text-[9px] text-slate-400 uppercase bg-slate-100/50 border-b border-slate-100 font-bold tracking-wider">
                    <tr>
                        <th class="px-4 py-4 w-1/4">Tanggal & Deskripsi</th>
                        <th class="px-2 py-4 text-center">Tipe Hari</th>
                        <th class="px-2 py-4 text-center">Lembur Kotor</th>
                        <th class="px-2 py-4 text-center">Pot. Istirahat</th>
                        <th class="px-2 py-4 text-center">Jam Efektif</th>
                        <th class="px-2 py-4 text-center">Pengali</th>
                        <th class="px-2 py-4 text-center">Status</th>
                        <th class="px-4 py-4 text-right w-1/6">Upah Lembur</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 font-medium">
                    @forelse($overtimes as $overtime)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-4">
                                <span class="font-bold text-slate-800 text-xs block">
                                    {{ \Carbon\Carbon::parse($overtime->tanggal_masuk)->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-[10px] text-slate-400 block mt-1 font-semibold truncate max-w-[180px]" title="{{ $overtime->nama_lemburan }}">
                                    {{ $overtime->nama_lemburan }}
                                </span>
                            </td>
                            <td class="px-2 py-4 text-center whitespace-nowrap">
                                @if($overtime->is_weekend)
                                    <span class="px-2 py-0.5 rounded bg-red-50 text-red-600 text-[9px] font-extrabold uppercase border border-red-100 tracking-wide">Hari Libur</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[9px] font-extrabold uppercase border border-slate-200 tracking-wide">Hari Kerja</span>
                                @endif
                            </td>
                            <td class="px-2 py-4 text-center font-bold text-slate-700 whitespace-nowrap">
                                {{ number_format($overtime->total_jam, 1, ',', '.') }} Jam
                            </td>
                            <td class="px-2 py-4 text-center whitespace-nowrap">
                                @if($overtime->potongan_jam > 0)
                                    <span class="text-red-500 font-bold text-xs">-{{ $overtime->potongan_jam }} Jam</span>
                                @else
                                    <span class="text-slate-400 font-bold">-</span>
                                @endif
                            </td>
                            <td class="px-2 py-4 text-center font-extrabold text-green-600 whitespace-nowrap">
                                {{ number_format($overtime->jam_efektif, 1, ',', '.') }} Jam
                            </td>
                            <td class="px-2 py-4 text-center font-bold text-indigo-600 whitespace-nowrap">
                                {{ number_format($overtime->multiplier, 2, ',', '.') }}x
                            </td>
                            <td class="px-2 py-4 text-center whitespace-nowrap">
                                @if($overtime->status == 'waiting' || $overtime->status == 'pending')
                                    <span class="bg-yellow-50 text-yellow-600 text-[9px] font-extrabold px-2.5 py-0.5 rounded-full border border-yellow-100 uppercase tracking-widest">MENUNGGU</span>
                                @elseif($overtime->status == 'approved')
                                    <span class="bg-green-50 text-green-600 text-[9px] font-extrabold px-2.5 py-0.5 rounded-full border border-green-100 uppercase tracking-widest">DISETUJUI</span>
                                @elseif($overtime->status == 'rejected')
                                    <span class="bg-red-50 text-red-600 text-[9px] font-extrabold px-2.5 py-0.5 rounded-full border border-red-100 uppercase tracking-widest">DITOLAK</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right font-extrabold text-slate-800 text-xs whitespace-nowrap">
                                Rp {{ number_format($overtime->nominal_lembur, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Tidak ada riwayat pengajuan lembur di periode ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($overtimes) > 0)
                    <tfoot class="bg-slate-900 text-white font-bold text-xs border-t-2 border-slate-950">
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-right uppercase tracking-wider font-outfit text-[10px]">Total Upah Lembur Disetujui (Approved):</td>
                            <td class="px-2 py-3 text-center whitespace-nowrap">
                                <span class="bg-emerald-500/20 text-emerald-300 px-2 py-0.5 rounded border border-emerald-500/30 text-[8px] font-black">DISETUJUI</span>
                            </td>
                            <td class="px-4 py-3 text-right text-emerald-400 font-black text-sm whitespace-nowrap">
                                Rp {{ number_format($totalApprovedUang, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="border-t border-slate-800">
                            <td colspan="6" class="px-4 py-3 text-right uppercase tracking-wider font-outfit text-[10px]">Total Estimasi Upah Menunggu (Pending):</td>
                            <td class="px-2 py-3 text-center whitespace-nowrap">
                                <span class="bg-amber-500/20 text-amber-300 px-2 py-0.5 rounded border border-amber-500/30 text-[8px] font-black">PENDING</span>
                            </td>
                            <td class="px-4 py-3 text-right text-amber-400 font-black text-sm whitespace-nowrap">
                                Rp {{ number_format($totalWaitingUang, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
