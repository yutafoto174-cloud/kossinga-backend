<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} Admin</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <!-- Ikon Font Awesome (dipakai tag <i class="fas ..."> seperti di modul) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Tailwind dan Alpine via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Nunito', sans-serif; }
        .bg-sidebar { background: #3d68ff; }
        .active-nav-link { background: #1947ee; }
        .nav-item:hover { background: #1947ee; }
        .account-link:hover { background: #3d68ff; color: white; }
    </style>
</head>
<body class="bg-gray-100 flex">

    <!-- ================= SIDEBAR (desktop) ================= -->
    <aside class="relative bg-sidebar h-screen w-64 hidden sm:block shadow-xl">
        <div class="p-6">
            <a href="{{ route('admin.kos.index') }}" class="text-white text-2xl font-semibold uppercase">KosSinga Admin</a>

            <a href="{{ route('admin.kos.create') }}"
               class="w-full bg-white text-blue-600 font-semibold py-2 mt-5 rounded-br-lg rounded-bl-lg rounded-tr-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
                <i class="fas fa-plus mr-3"></i> Tambah Kos
            </a>
        </div>

        <nav class="text-white text-base font-semibold pt-3">
            <a href="{{ route('dashboard') }}"
               class="flex items-center text-white py-4 pl-6 nav-item {{ request()->routeIs('dashboard') ? 'active-nav-link' : 'opacity-75' }}">
                <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
            </a>
            <a href="{{ route('admin.kos.index') }}"
               class="flex items-center text-white py-4 pl-6 nav-item {{ request()->routeIs('admin.kos.*') ? 'active-nav-link' : 'opacity-75' }}">
                <i class="fas fa-home mr-3"></i> Data Kos
            </a>
            <a href="{{ route('home') }}" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-globe mr-3"></i> Ke Website
            </a>
        </nav>
    </aside>

    <!-- ================= AREA KANAN ================= -->
    <div class="flex-1 flex flex-col h-screen min-w-0">

        <!-- Header desktop -->
        <header class="w-full items-center bg-white py-2 px-6 hidden sm:flex">
            <div class="w-1/2 text-sm text-gray-600">Halo, {{ Auth::user()->name }}</div>

            <div x-data="{ isOpen: false }" class="relative w-1/2 flex justify-end">
                <button @click="isOpen = !isOpen"
                        class="relative z-10 w-12 h-12 rounded-full border-4 border-gray-400 hover:border-gray-300 focus:outline-none bg-blue-600 text-white font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </button>
                <button x-show="isOpen" @click="isOpen = false" class="h-full w-full fixed inset-0 cursor-default"></button>
                <div x-show="isOpen" class="absolute w-40 bg-white rounded-lg shadow-lg py-2 mt-16 z-20">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 account-link">Sign Out</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Header mobile + menu -->
        <header x-data="{ isOpen: false }" class="w-full bg-sidebar py-5 px-6 sm:hidden">
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.kos.index') }}" class="text-white text-2xl font-semibold uppercase">KosSinga Admin</a>
                <button @click="isOpen = !isOpen" class="text-white text-3xl focus:outline-none">
                    <i x-show="!isOpen" class="fas fa-bars"></i>
                    <i x-show="isOpen" class="fas fa-times"></i>
                </button>
            </div>
            <nav :class="isOpen ? 'flex' : 'hidden'" class="flex-col pt-4 text-white hidden">
                <a href="{{ route('dashboard') }}" class="py-2 pl-4 nav-item">Dashboard</a>
                <a href="{{ route('admin.kos.index') }}" class="py-2 pl-4 nav-item">Data Kos</a>
                <a href="{{ route('admin.kos.create') }}" class="py-2 pl-4 nav-item">Tambah Kos</a>
                <a href="{{ route('home') }}" class="py-2 pl-4 nav-item">Ke Website</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="py-2 pl-4 nav-item w-full text-left">Sign Out</button>
                </form>
            </nav>
        </header>

        <!-- Konten -->
        <div class="flex-1 overflow-y-auto border-t flex flex-col">
            <main class="w-full flex-grow p-6">
                {{ $slot }}
            </main>
            <footer class="w-full bg-white text-right p-4 text-sm text-gray-600">
                © 2026 KosSinga Admin
            </footer>
        </div>
    </div>

</body>
</html>