<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="KosSinga: cari kos mahasiswa di Singaraja">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js untuk menu mobile -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased">
    <div class="flex flex-col min-h-screen bg-gray-50">

        {{-- Navigasi (Langkah 6) --}}
        @include('layouts.navigation-home')

        {{-- Isi halaman yang berubah-ubah --}}
        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="bg-gray-800 text-white text-center p-4">
            © 2026 KosSinga — Prototipe Pencari Kos Mahasiswa Singaraja
        </footer>

    </div>
</body>
</html>