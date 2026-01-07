<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title','Sistem Informasi Keuangan')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| AMBIL USER LOGIN (AMAN)
|--------------------------------------------------------------------------
| 1. Coba Auth::user() (kalau nanti pakai Auth)
| 2. Kalau null → ambil dari database pakai session user_id
*/
$auth = Auth::user();

if (!$auth && session('user_id')) {
    $auth = DB::table('user')
        ->where('user_id', session('user_id'))
        ->first();
}
@endphp

<body
x-data="{
    sidebarOpen: true,
    mobileOpen: false,
    isMobile: window.innerWidth < 768,

    init() {
        this.syncLayout()
        window.addEventListener('resize', () => {
            this.isMobile = window.innerWidth < 768
            this.syncLayout()
        })
    },

    syncLayout() {
        if (this.isMobile) {
            this.mobileOpen = false
        } else {
            this.sidebarOpen = true
            this.mobileOpen = false
        }
    },

    toggleSidebar() {
        if (this.isMobile) {
            this.mobileOpen = !this.mobileOpen
        } else {
            this.sidebarOpen = !this.sidebarOpen
        }
    }
}"
x-cloak
class="bg-slate-100 text-gray-800 overflow-x-hidden">

{{-- ================= SIDEBAR ================= --}}
<aside
:class="{
    'w-72 translate-x-0': (!isMobile && sidebarOpen) || (isMobile && mobileOpen),
    'w-20 translate-x-0': (!isMobile && !sidebarOpen),
    '-translate-x-full': (isMobile && !mobileOpen)
}"
class="fixed top-0 left-0 h-screen
       bg-[#e8543e] text-white z-40
       transition-all duration-300
       flex flex-col overflow-hidden">

    {{-- LOGO --}}
    <div class="px-6 py-5 border-b border-white/20">
        <img src="{{ asset('img/logo.svg') }}"
             class="h-10 mx-auto object-contain"
             :class="(!isMobile && sidebarOpen) || isMobile ? 'w-56' : 'w-10'">
    </div>

    {{-- USER --}}
    <div class="flex items-center gap-3 px-6 py-4 border-b border-white/20">
        <div class="w-10 h-10 rounded-full border-2 border-white shadow overflow-hidden">
            <img src="{{ $auth && $auth->user_foto
                ? asset($auth->user_foto)
                : asset('img/use.png') }}"
                class="w-full h-full object-cover">
        </div>

        <div x-show="(!isMobile && sidebarOpen) || isMobile">
            <p class="text-sm font-semibold">
                {{ $auth->user_nama ?? '-' }}
            </p>
            <p class="text-xs text-white/80">
                {{ isset($auth->user_level) ? ucfirst($auth->user_level) : '-' }}
            </p>
        </div>
    </div>

    {{-- MENU --}}
    <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
        @php
        $menu = [
            ['dashboard','speedometer2','Dashboard'],
            ['mahasiswa.index','mortarboard','Mahasiswa'],
            ['kategori.index','folder2','Kategori'],
            ['transaksi.index','cash-stack','Transaksi'],
            ['laporan.index','file-earmark-text','Laporan'],
        ];
        @endphp

        @foreach($menu as $m)
        <a href="{{ route($m[0]) }}"
           @click="if (isMobile) mobileOpen = false"
           class="flex items-center gap-3 px-4 py-3 rounded hover:bg-black/20">
            <i class="bi bi-{{ $m[1] }} text-lg"></i>
            <span x-show="(!isMobile && sidebarOpen) || isMobile">
                {{ $m[2] }}
            </span>
        </a>
        @endforeach

        {{-- MENU USER (ADMIN ONLY) --}}
        @if($auth && $auth->user_level === 'administrator')
        <a href="{{ route('user.index') }}"
           @click="if (isMobile) mobileOpen = false"
           class="flex items-center gap-3 px-4 py-3 rounded hover:bg-black/20">
            <i class="bi bi-people text-lg"></i>
            <span x-show="(!isMobile && sidebarOpen) || isMobile">
                Manajemen User
            </span>
        </a>
        @endif
    </nav>

    {{-- LOGOUT --}}
    <div class="p-4 border-t border-white/20">
        <a href="{{ route('logout') }}"
           class="block text-center bg-white text-[#e8543e] py-2 rounded font-bold">
            <span x-show="(!isMobile && sidebarOpen) || isMobile">Logout</span>
            <i x-show="!sidebarOpen && !isMobile" class="bi bi-box-arrow-right text-xl"></i>
        </a>
    </div>
</aside>

{{-- OVERLAY MOBILE --}}
<div x-show="mobileOpen"
     @click="mobileOpen = false"
     class="fixed inset-0 bg-black/40 z-30 md:hidden"></div>

{{-- ================= CONTENT ================= --}}
<div
:class="{
    'ml-72': !isMobile && sidebarOpen,
    'ml-20': !isMobile && !sidebarOpen,
    'ml-0': isMobile
}"
class="min-h-screen transition-all duration-300 flex flex-col">

    {{-- HEADER --}}
    <header class="sticky top-0 z-30 bg-white px-4 py-3
                   border-b-4 border-[#e8543e]
                   flex items-center gap-3">

        <button @click="toggleSidebar()" class="text-[#e8543e] text-2xl">
            <i class="bi bi-list"></i>
        </button>

        <h1 class="font-semibold text-[#e8543e] flex-1">
            @yield('title')
        </h1>

        <span class="hidden sm:block text-sm text-gray-600">
            {{ $auth->user_nama ?? '-' }}
            ({{ isset($auth->user_level) ? ucfirst($auth->user_level) : '-' }})
        </span>
    </header>

    <main class="flex-1 p-4 md:p-6">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stack('scripts')

</body>
</html>
