<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sistem Keuangan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#E8543E'
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary/20 to-primary/40">

<div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
    
    {{-- Title --}}
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">SISTEM KEUANGAN</h2>
    </div>

    {{-- Alert --}}
    @if(session('alert') == 'gagal')
        <div class="mb-4 rounded-lg bg-red-100 text-red-700 px-4 py-2 text-sm">
            LOGIN GAGAL! USERNAME DAN PASSWORD SALAH!
        </div>
    @elseif(session('alert') == 'logout')
        <div class="mb-4 rounded-lg bg-green-100 text-green-700 px-4 py-2 text-sm">
            ANDA TELAH BERHASIL LOGOUT
        </div>
    @elseif(session('alert') == 'belum_login')
            {{-- <div class="mb-4 rounded-lg bg-yellow-100 text-yellow-700 px-4 py-2 text-sm">
                ANDA HARUS LOGIN UNTUK MENGAKSES DASHBOARD
            </div> --}}
    @endif

    {{-- Logo --}}
    <div class="flex justify-center mb-6">
       <img src="{{ asset('img/logo1.png') }}" class="w-45 h-35">

    </div>

    {{-- Form --}}
    <form action="{{ route('login.proses') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <input type="text" 
                   name="username"
                   placeholder="Username"
                   required
                   class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>

        <div>
            <input type="password" 
                   name="password"
                   placeholder="Password"
                   required
                   class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>

        <button type="submit"
                class="w-full bg-primary hover:bg-primary/90 text-white py-3 rounded-lg font-semibold transition">
            Sign In
        </button>
    </form>
</div>

</body>
</html>
