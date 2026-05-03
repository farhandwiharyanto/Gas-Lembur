@extends('admin.layouts.app')
@section('header_title', 'Laporan & Export')
@section('content')

<div class="relative min-h-[80vh] flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Abstract Background Decorations -->
    <div class="absolute top-0 -left-4 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute top-0 -right-4 w-72 h-72 bg-rose-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-72 h-72 bg-amber-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

    <div class="max-w-3xl w-full space-y-8 relative z-10">
        <div class="text-center">
            <h1 class="text-4xl font-black text-slate-900 font-outfit uppercase tracking-tighter mb-2">Laporan Rekapitulasi</h1>
            <p class="text-slate-500 font-medium text-sm uppercase tracking-widest">Unduh data pengajuan lembur dalam format PDF Premium</p>
        </div>

        <div class="bg-white/70 backdrop-blur-xl rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-white/50 p-10 md:p-16">
            <div class="w-24 h-24 bg-gradient-to-tr from-indigo-600 to-violet-500 text-white rounded-3xl flex items-center justify-center mx-auto mb-10 shadow-xl shadow-indigo-200 transform hover:rotate-12 transition-transform duration-500">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>

            @if (session('error'))
                <div class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-600 text-xs font-bold rounded-2xl text-center uppercase tracking-wider">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.reports.export') }}" method="GET" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Periode Bulan</label>
                        <div class="relative">
                            <select name="month" class="appearance-none w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 focus:bg-white outline-none transition-all text-sm font-bold text-slate-700 cursor-pointer">
                                <option value="">Semua Bulan (All Months)</option>
                                @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $idx => $m)
                                    <option value="{{ $idx + 1 }}">{{ $m }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Periode Tahun</label>
                        <div class="relative">
                            <select name="year" class="appearance-none w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-500 focus:bg-white outline-none transition-all text-sm font-bold text-slate-700 cursor-pointer">
                                <option value="">Semua Tahun (All Years)</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="group relative w-full flex items-center justify-center px-8 py-5 bg-slate-900 text-white font-black rounded-2xl text-xs transition-all hover:bg-black hover:shadow-2xl hover:shadow-slate-200 active:scale-[0.98] uppercase tracking-[0.2em]">
                        <span class="absolute right-8 transition-transform group-hover:translate-x-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </span>
                        Download PDF Rekapitulasi
                    </button>
                    <p class="text-center mt-6 text-[10px] text-slate-400 font-bold uppercase tracking-widest">Format: PDF (Landscape) • Resolusi Tinggi</p>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>

@endsection
