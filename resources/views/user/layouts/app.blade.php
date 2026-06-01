<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Gas-Lembur') }} - Persetujuan Lembur</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('images/logo-gas-lembur.png') }}" type="image/png">
    
    <!-- Google Fonts -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased text-gray-900">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-72 bg-slate-900 text-slate-100 shadow-2xl flex-shrink-0 flex flex-col hidden md:flex border-r border-slate-800">
        <div class="h-24 flex items-center px-6 bg-slate-950/50 backdrop-blur-sm border-b border-slate-800/50">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo-gas-lembur.png') }}" alt="Logo" class="h-10 w-10 object-contain brightness-110 shadow-lg p-1 bg-white/5 rounded-lg">
                <div class="flex flex-col">
                    <span class="text-xl font-bold text-white tracking-tight font-outfit uppercase">GAS-LEMBUR</span>
                    <span class="text-[10px] text-indigo-400 font-bold tracking-[0.2em] -mt-1 opacity-80">EMPLOYEE PORTAL</span>
                </div>
            </div>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-3 overflow-y-auto">
            <!-- 1. Dashboard -->
            <a href="{{ route('user.dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('user.dashboard') ? 'bg-indigo-600 text-white font-bold' : 'hover:bg-indigo-800 hover:text-white' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>

            <!-- 2. Lembur Dropdown -->
            <div x-data="{ open: {{ request()->routeIs('overtime.create') || request()->routeIs('user.history.index') || request()->routeIs('user.perhitungan.index') || request()->routeIs('overtime.edit') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-800 hover:text-white rounded-lg transition-colors text-slate-100 {{ request()->routeIs('overtime.create') || request()->routeIs('user.history.index') || request()->routeIs('user.perhitungan.index') || request()->routeIs('overtime.edit') ? 'bg-indigo-900/40 text-indigo-400 font-bold border-l-4 border-indigo-500' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('overtime.create') || request()->routeIs('user.history.index') || request()->routeIs('user.perhitungan.index') || request()->routeIs('overtime.edit') ? 'text-indigo-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Lembur</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
                <div x-show="open" x-cloak class="pl-6 space-y-1 mt-1 transition-all duration-300">
                    <a href="{{ route('overtime.create') }}" class="flex items-center px-4 py-2 text-xs {{ request()->routeIs('overtime.create') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-300 hover:bg-indigo-800' }} rounded-md transition-colors">
                        Input Lembur
                    </a>
                    <a href="{{ route('user.history.index') }}" class="flex items-center px-4 py-2 text-xs {{ request()->routeIs('user.history.index') || request()->routeIs('overtime.edit') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-300 hover:bg-indigo-800' }} rounded-md transition-colors">
                        Riwayat Lembur
                    </a>
                    <a href="{{ route('user.perhitungan.index') }}" class="flex items-center px-4 py-2 text-xs {{ request()->routeIs('user.perhitungan.index') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-300 hover:bg-indigo-800' }} rounded-md transition-colors">
                        Perhitungan Lembur
                    </a>
                </div>
            </div>

            <!-- 3. Cuti Dropdown -->
            <div x-data="{ open: {{ request()->routeIs('user.cuti.*') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-800 hover:text-white rounded-lg transition-colors text-slate-100 {{ request()->routeIs('user.cuti.*') ? 'bg-indigo-900/40 text-indigo-400 font-bold border-l-4 border-indigo-500' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('user.cuti.*') ? 'text-indigo-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>Cuti</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
                <div x-show="open" x-cloak class="pl-6 space-y-1 mt-1 transition-all duration-300">
                    <a href="{{ route('user.cuti.create') }}" class="flex items-center px-4 py-2 text-xs {{ request()->routeIs('user.cuti.create') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-300 hover:bg-indigo-800' }} rounded-md transition-colors">
                        Input Cuti
                    </a>
                    <a href="{{ route('user.cuti.index') }}" class="flex items-center px-4 py-2 text-xs {{ request()->routeIs('user.cuti.index') || request()->routeIs('user.cuti.edit') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-300 hover:bg-indigo-800' }} rounded-md transition-colors">
                        Riwayat Cuti
                    </a>
                    <a href="{{ route('user.cuti.dashboard') }}" class="flex items-center px-4 py-2 text-xs {{ request()->routeIs('user.cuti.dashboard') ? 'bg-indigo-600 text-white font-bold' : 'text-slate-300 hover:bg-indigo-800' }} rounded-md transition-colors">
                        Dashboard Cuti
                    </a>
                </div>
            </div>

            <!-- 4. Profil Karyawan -->
            <a href="{{ route('user.profile.edit') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('user.profile.*') ? 'bg-indigo-600 text-white font-bold' : 'hover:bg-indigo-800 hover:text-white' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Profil Karyawan
            </a>
        </nav>
        
        <div class="p-6 border-t border-slate-800/50 bg-slate-950/20">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-5 py-3 text-red-400 border border-red-900/30 hover:bg-red-900/20 hover:text-red-300 rounded-xl transition-all duration-300 font-semibold text-sm group">
                    <svg class="w-5 h-5 mr-3 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout Akun
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation (Mobile & Header) -->
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10 hidden md:flex">
            <div class="text-sm font-medium text-gray-500">
                @yield('header_title', 'Employee Portal')
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <span class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </header>
        
        <!-- Mobile Header (Visible on small screens) -->
        <header class="bg-slate-900 shadow-lg h-16 flex items-center justify-between px-4 z-10 md:hidden text-white border-b border-slate-800">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo-gas-lembur.png') }}" alt="Logo" class="h-8 w-8 object-contain brightness-110">
                <span class="text-base font-bold text-white tracking-tight uppercase font-outfit">GAS-LEMBUR</span>
            </div>
            <!-- Mobile Menu Button (Hamburger) -->
            <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="p-2 rounded-md hover:bg-slate-800 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto bg-gray-50 p-6 flex flex-col items-center">
            <div class="w-full max-w-[1400px]">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm rounded-r" role="alert">
                        <span class="block sm:inline font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm rounded-r" role="alert">
                        <span class="block sm:inline font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</div>

<!-- Custom Premium Confirmation Modal -->
<div x-data="globalConfirmModal()"
     @open-confirm.window="open($event.detail)"
     x-show="isOpen"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
     
    <!-- Backdrop Blur -->
    <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Modal Content Positioner -->
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div x-show="isOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-[2.5rem] bg-slate-900 border border-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg p-8 relative z-10 text-white">
             
            <!-- Decorative Glow background -->
            <div class="absolute -right-16 -top-16 w-36 h-36 rounded-full blur-3xl opacity-20"
                 :class="{
                    'bg-emerald-500': type === 'success' || type === 'approve',
                    'bg-red-500': type === 'danger' || type === 'reject',
                    'bg-indigo-500': type === 'info' || type === 'bulk'
                 }"></div>
            <div class="absolute -left-16 -bottom-16 w-36 h-36 rounded-full blur-3xl opacity-10"
                 :class="{
                    'bg-emerald-500': type === 'success' || type === 'approve',
                    'bg-red-500': type === 'danger' || type === 'reject',
                    'bg-indigo-500': type === 'info' || type === 'bulk'
                 }"></div>

            <div class="relative z-10">
                <!-- Icon Header -->
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl mb-6 shadow-lg border"
                     :class="{
                        'bg-emerald-500/10 border-emerald-500/20 text-emerald-400': type === 'success' || type === 'approve',
                        'bg-red-500/10 border-red-500/20 text-red-400': type === 'danger' || type === 'reject',
                        'bg-indigo-500/10 border-indigo-500/20 text-indigo-400': type === 'info' || type === 'bulk'
                     }">
                     <!-- Approve/Success Icon -->
                     <template x-if="type === 'success' || type === 'approve'">
                         <svg class="h-8 w-8 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                         </svg>
                     </template>
                     <!-- Reject/Danger Icon -->
                     <template x-if="type === 'danger' || type === 'reject'">
                         <svg class="h-8 w-8 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                         </svg>
                     </template>
                     <!-- Info/Bulk Icon -->
                     <template x-if="type === 'info' || type === 'bulk'">
                         <svg class="h-8 w-8 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                         </svg>
                     </template>
                </div>

                <!-- Text content -->
                <div class="text-center">
                    <h3 class="text-xl font-black font-outfit uppercase tracking-tight text-white mb-2" x-text="title"></h3>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed px-2" x-html="message"></p>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <button type="button" 
                            @click="close()" 
                            class="w-full px-6 py-3.5 bg-slate-800 hover:bg-slate-700/80 text-slate-300 font-extrabold rounded-2xl transition-all border border-slate-700/50 uppercase tracking-widest text-xs active:scale-95" 
                            x-text="cancelText"></button>
                    <button type="button" 
                            @click="confirm()" 
                            class="w-full px-6 py-3.5 font-extrabold rounded-2xl transition-all uppercase tracking-widest text-xs active:scale-95 shadow-lg"
                            :class="{
                                'bg-emerald-600 hover:bg-emerald-500 text-white shadow-emerald-900/20': type === 'success' || type === 'approve',
                                'bg-red-600 hover:bg-red-500 text-white shadow-red-900/20': type === 'danger' || type === 'reject',
                                'bg-indigo-600 hover:bg-indigo-500 text-white shadow-indigo-900/20': type === 'info' || type === 'bulk'
                            }"
                            x-text="confirmText"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function globalConfirmModal() {
        return {
            isOpen: false,
            title: '',
            message: '',
            confirmText: 'Ya, Lanjutkan',
            cancelText: 'Batal',
            type: 'info',
            onConfirm: null,
            
            open(detail) {
                this.title = detail.title || 'Konfirmasi';
                this.message = detail.message || 'Apakah Anda yakin?';
                this.confirmText = detail.confirmText || 'Ya, Lanjutkan';
                this.cancelText = detail.cancelText || 'Batal';
                this.type = detail.type || 'info';
                this.onConfirm = detail.onConfirm || null;
                this.isOpen = true;
            },
            
            close() {
                this.isOpen = false;
            },
            
            confirm() {
                if (typeof this.onConfirm === 'function') {
                    this.onConfirm();
                }
                this.close();
            }
        }
    }

    window.showConfirm = function(options) {
        window.dispatchEvent(new CustomEvent('open-confirm', {
            detail: options
        }));
    };
</script>

@stack('scripts')
</body>
</html>
