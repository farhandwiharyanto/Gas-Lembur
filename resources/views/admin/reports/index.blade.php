@extends('admin.layouts.app')
@section('header_title', 'Laporan & Export')
@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Laporan Lembur</h1>
        <p class="text-sm text-gray-500 mt-1">Unduh rekapitulasi data pengajuan lembur karyawan.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center max-w-2xl mx-auto mt-10">
    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
    </div>
    
    <h2 class="text-xl font-bold text-gray-800 mb-2">Export Data (CSV)</h2>
    <p class="text-gray-500 mb-8 max-w-md mx-auto">
        Anda dapat mengunduh seluruh master data lembur ke dalam format Spreadsheet / Excel (.csv) meliputi Tanggal, Nama, Divisi, dan Status.
    </p>
    
    <a href="{{ route('admin.reports.export') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 text-white font-semibold rounded-lg text-lg transition-all shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
        Download CSV Sekarang
    </a>
</div>

@endsection
