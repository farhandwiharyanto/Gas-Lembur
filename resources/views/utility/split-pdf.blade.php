@extends(auth()->user()->role === 'admin' ? 'admin.layouts.app' : (auth()->user()->role === 'pimpinan' ? 'pimpinan.layouts.app' : 'user.layouts.app'))

@section('header_title', 'Utilitas - Split PDF')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ isUploading: false, fileName: '' }">
    <!-- Header Section -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 font-outfit tracking-tight">Split PDF</h1>
            <p class="text-slate-500 mt-1 text-sm">Pecah file PDF yang berisi banyak halaman menjadi file PDF satuan (per halaman).</p>
        </div>
        <div class="hidden sm:flex p-3 bg-indigo-50 rounded-2xl border border-indigo-100 items-center justify-center shadow-sm">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
        <!-- Decorative blob -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        
        <div class="p-8 relative z-10">
            <form action="{{ route('utility.split-pdf.process') }}" method="POST" enctype="multipart/form-data" @submit="isUploading = true">
                @csrf
                
                <div class="space-y-6">
                    <!-- Upload Area -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih File PDF</label>
                        <div class="mt-1 flex justify-center px-6 pt-10 pb-12 border-2 border-dashed border-slate-300 rounded-3xl hover:border-indigo-400 hover:bg-indigo-50/30 transition-all duration-300 group relative"
                             :class="{ 'border-indigo-500 bg-indigo-50': fileName !== '' }">
                            <div class="space-y-2 text-center">
                                <svg class="mx-auto h-16 w-16 text-slate-400 group-hover:text-indigo-500 transition-colors duration-300" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-slate-600 justify-center">
                                    <label for="pdf_file" class="relative cursor-pointer bg-white rounded-md font-bold text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-1">
                                        <span>Upload file PDF</span>
                                        <input id="pdf_file" name="pdf_file" type="file" class="sr-only" accept="application/pdf" required @change="fileName = $event.target.files[0].name">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-slate-500 font-medium">Hanya file .PDF maksimal 10MB</p>
                                
                                <!-- File Name Display -->
                                <div x-show="fileName" x-cloak class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full text-sm font-semibold shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span x-text="fileName"></span>
                                </div>
                            </div>
                        </div>
                        @error('pdf_file')
                            <p class="mt-2 text-sm text-red-600 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Action Button -->
                    <div class="pt-4 flex justify-end">
                        <button type="submit" 
                                class="inline-flex items-center px-8 py-3.5 border border-transparent text-sm font-extrabold rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-600/30 transition-all duration-300 transform hover:-translate-y-1"
                                :disabled="isUploading"
                                :class="{ 'opacity-75 cursor-not-allowed': isUploading }">
                            <svg x-show="!isUploading" class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            
                            <svg x-show="isUploading" x-cloak class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            
                            <span x-text="isUploading ? 'Memproses PDF...' : 'Proses Split PDF'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Info Footer -->
        <div class="bg-slate-50/80 p-6 border-t border-slate-100 flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-semibold text-slate-800">Bagaimana Cara Kerjanya?</h3>
                <div class="mt-2 text-sm text-slate-600">
                    <p>File PDF yang Anda upload akan diproses, dan setiap halaman akan dipisahkan menjadi file PDF individu. Semua file tersebut akan disatukan dalam sebuah file <strong>.ZIP</strong> yang akan otomatis ter-download setelah proses selesai.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
