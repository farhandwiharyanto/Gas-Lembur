<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Lembur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-10">

<div class="max-w-md w-full mx-auto bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Masuk Akun</h1>
        <p class="text-gray-500 mt-2 text-sm">Silakan login untuk mengakses sistem lembur.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 text-sm" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-5">
        @csrf
        
        <div>
            <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username (Inisial)</label>
            <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                placeholder="Masukkan inisial 3 huruf (cth: adm)">
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
            <input type="password" name="password" id="password" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                placeholder="Masukkan password">
        </div>

        <div class="pt-2">
            <button type="submit" 
                class="w-full px-8 py-3 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 text-white font-semibold rounded-xl text-lg transition-all shadow-md">
                Masuk
            </button>
        </div>
    </form>
</div>

</body>
</html>
