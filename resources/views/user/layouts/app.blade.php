<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal - Persetujuan Lembur</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
    <!-- Alpine.js (for simple interactions if needed) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased text-gray-900">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-indigo-900 text-indigo-100 shadow-xl flex-shrink-0 flex flex-col hidden md:flex">
        <div class="h-16 flex items-center px-6 border-b border-indigo-800 bg-indigo-950">
            <h1 class="text-xl font-bold text-white tracking-widest">EMPLOYEE</h1>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('overtime.create') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('overtime.create') ? 'bg-indigo-600 text-white' : 'hover:bg-indigo-800 hover:text-white' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Lembur
            </a>
            
            <a href="{{ route('user.history.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('user.history.*') ? 'bg-indigo-600 text-white' : 'hover:bg-indigo-800 hover:text-white' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat Lembur
            </a>
            
            <a href="{{ route('user.profile.edit') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('user.profile.*') ? 'bg-indigo-600 text-white' : 'hover:bg-indigo-800 hover:text-white' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Profil Karyawan
            </a>
        </nav>
        
        <div class="p-4 border-t border-indigo-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-3 text-red-300 border border-indigo-700 hover:bg-indigo-800 hover:text-red-200 rounded-lg transition-colors font-medium">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
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
        <header class="bg-indigo-900 shadow-sm h-16 flex items-center justify-between px-4 z-10 md:hidden text-white">
            <h1 class="text-lg font-bold tracking-widest">EMPLOYEE</h1>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto bg-gray-50 p-6 flex flex-col items-center">
            <div class="w-full max-w-5xl">
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

@stack('scripts')
</body>
</html>
