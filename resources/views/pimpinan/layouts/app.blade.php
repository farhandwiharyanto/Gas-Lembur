<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pimpinan Portal - Persetujuan Lembur</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Fonts -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased text-gray-900">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-emerald-900 text-emerald-100 shadow-xl flex-shrink-0 flex flex-col hidden md:flex">
        <div class="h-16 flex items-center px-6 border-b border-emerald-800 bg-emerald-950">
            <h1 class="text-xl font-bold text-white tracking-widest">PIMPINAN</h1>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('pimpinan.dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('pimpinan.dashboard') ? 'bg-emerald-600 text-white' : 'hover:bg-emerald-800 hover:text-white' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                Dashboard Chart
            </a>
            
            <a href="{{ route('pimpinan.approvals') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('pimpinan.approvals') ? 'bg-emerald-600 text-white' : 'hover:bg-emerald-800 hover:text-white' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Persetujuan Lembur
            </a>
            
            <a href="{{ route('pimpinan.history') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('pimpinan.history') ? 'bg-emerald-600 text-white' : 'hover:bg-emerald-800 hover:text-white' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Riwayat Disetujui
            </a>
            
            <a href="{{ route('user.profile.edit') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('user.profile.*') ? 'bg-emerald-600 text-white' : 'hover:bg-emerald-800 hover:text-white' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Profil Saya
            </a>
        </nav>
        
        <div class="p-4 border-t border-emerald-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-3 text-red-300 border border-emerald-700 hover:bg-emerald-800 hover:text-red-200 rounded-lg transition-colors font-medium">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout Akun
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10 hidden md:flex">
            <div class="text-sm font-medium text-gray-500">
                @yield('header_title', 'Pimpinan Portal')
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold">
                        {{ substr(auth()->user()->name ?? 'P', 0, 1) }}
                    </div>
                    <span class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto bg-gray-50 p-6 flex flex-col items-center">
            <div class="w-full max-w-6xl">
                @yield('content')
            </div>
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
