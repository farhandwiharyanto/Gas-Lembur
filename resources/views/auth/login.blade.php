<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Gas-Lembur') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('images/logo-gas-lembur.png') }}" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background: radial-gradient(circle at top right, #1e293b, #0f172a); }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 antialiased">

<div class="max-w-md w-full mx-auto">
    <!-- Logo & Title Area -->
    <div class="text-center mb-10 group">
        <div class="inline-flex p-4 rounded-3xl bg-white/5 border border-white/10 shadow-2xl mb-6 transition-transform duration-500 group-hover:scale-110">
            <img src="{{ asset('images/logo-gas-lembur.png') }}" alt="Logo" class="h-20 w-20 object-contain brightness-110">
        </div>
        <h1 class="text-4xl font-extrabold text-white tracking-tight font-outfit uppercase">GAS-LEMBUR</h1>
        <p class="text-slate-400 mt-2 text-sm font-medium tracking-wide uppercase opacity-70">Sistem Informasi Lembur Karyawan</p>
    </div>

    <!-- Login Card -->
    <div class="glass p-10 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)]">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-white mb-2">Selamat Datang</h2>
            <p class="text-slate-400 text-sm">Silakan login untuk mengakses portal Anda.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-5 py-4 rounded-2xl mb-8 text-sm" role="alert">
                <ul class="space-y-1 list-none p-0 m-0">
                    @foreach ($errors->all() as $error)
                        <li class="flex items-center">
                            <span class="mr-2">⚠️</span> {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-1.5">
                <label for="username" class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Username (Inisial)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">👤</span>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                        class="w-full pl-12 pr-6 py-4 bg-white/5 border border-white/10 rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-white outline-none transition-all duration-300 placeholder-slate-600"
                        placeholder="Contoh: adm">
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Password Lokal</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">🔒</span>
                    <input type="password" name="password" id="password" required
                        class="w-full pl-12 pr-6 py-4 bg-white/5 border border-white/10 rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-white outline-none transition-all duration-300 placeholder-slate-600"
                        placeholder="••••••••">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" 
                    class="w-full px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl text-lg transition-all duration-300 shadow-[0_10px_20px_rgba(79,70,229,0.3)] hover:shadow-[0_15px_25px_rgba(79,70,229,0.4)] active:scale-95">
                    Masuk Sekarang
                </button>
            </div>
        </form>
    </div>

    <!-- Footer Area -->
    <div class="mt-12 text-center">
        <p class="text-slate-500 text-xs font-medium uppercase tracking-[0.2em]">&copy; 2026 Gas-Lembur Portal</p>
    </div>
</div>

</body>
</html>
