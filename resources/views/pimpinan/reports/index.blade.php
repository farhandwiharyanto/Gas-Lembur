@extends('pimpinan.layouts.app')
@section('header_title', 'Laporan Rekapitulasi')
@section('content')

<div class="mb-6 flex justify-between items-end px-4">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 font-outfit uppercase tracking-tight">Laporan Lembur Bagian</h1>
        <p class="text-sm text-gray-500 mt-1 font-medium">Unduh rekapitulasi data pengajuan lembur karyawan di bagian Anda.</p>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10 text-center max-w-2xl mx-auto mt-10">
    <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-emerald-100">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
    </div>
    
    <h2 class="text-2xl font-extrabold text-slate-800 mb-2 font-outfit uppercase tracking-tight">Export Rekap Lembur</h2>
    <p class="text-slate-500 mb-8 max-w-md mx-auto text-sm font-medium">
        Pilih periode laporan untuk mengunduh rekapitulasi lembur karyawan di bagian <strong>{{ auth()->user()->bagian }}</strong>.
    </p>
    
    <form action="{{ route('admin.reports.export') }}" method="GET" class="space-y-6">
        <div class="grid grid-cols-2 gap-4">
            <div class="text-left">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Periode Bulan</label>
                <select name="month" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all text-sm font-bold text-slate-700">
                    <option value="">Semua Bulan</option>
                    @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $idx => $m)
                        <option value="{{ $idx + 1 }}">{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="text-left">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2 px-1">Tahun Laporan</label>
                <select name="year" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all text-sm font-bold text-slate-700">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="w-full flex items-center justify-center px-8 py-5 bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-200 text-white font-extrabold rounded-[1.5rem] text-sm transition-all shadow-xl hover:shadow-emerald-500/20 font-outfit uppercase tracking-widest">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Download Laporan (CSV)
        </button>
    </form>
</div>

@endsection
